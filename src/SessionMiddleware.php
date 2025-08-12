<?php declare(strict_types=1);
/**
 * MIT License
 * 
 * Copyright (c) 2026 Nicholas English
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace Cytrenna\Session;

/**
 * Callable session middleware.
 */
class SessionMiddleware
{
    /** @var \Cytrenna\Session\SessionManager $session The session manager. */
    protected SessionManager $session;

    /**
     * Construct the session middleware.
     *
     * @param \Cytrenna\Session\SessionManager $session The session manager.
     *
     * @return void Returns nothing.
     */
    public function __construct(SessionManager $session)
    {
        $this->session = $session;
    }

    public function handle(callable $next)
    {
        // Start the session if not already started.
        if ($this->session->isRunning()) {
            // Validate the session fingerprint.
            if ($this->session->fingerprintEnabled()) {
                $fingerprint = $this->session->generateUniqueFingerprint();
                if ($this->session->has('cytrenna.fingerprint')) {
                    if (!hash_equals($this->session->has('cytrenna.fingerprint'), $fingerprint)) {
                        $this->session->stopSession();
                        throw new InvalidFingerprintException('The fingerprint supplied is invalid.');
                    }
                } else {
                    $this->session->put('cytrenna.fingerprint', $fingerprint);
                }
            }
            // Prevent AJAX requests.
            if ($this->session->disallowAjaxRequests()) {
                if ($this->session->isAjaxRequest()) {
                    throw new AjaxRestrictedException('This application does not allow ajax requests.');
                }
            }
            // Start the session.
            $this->session->start();
        }
        // Call the next middleware or controller.
        $response = $next();
        // Returned the response.
        return $response;
    }
}
