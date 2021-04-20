<?php declare(strict_types=1);
/**
 * MIT License
 * 
 * Copyright (c) 2021 Nicholas English
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

namespace Omatamix\SessionLock;

/**
 * Secure session management.
 */
interface SessionManagerInterface
{
    /**
     * Construct a new session manager.
     *
     * @param array $options The session manager options.
     *
     * @return void Returns nothing.
     */
    public function __construct(array $options = []);

    /**
     * Starts or resumes a session.
     *
     * @throws Exception\InvalidFingerprintException If the fingerprint from the user does not match the one
     *                                               binded to the session.
     *
     * @return bool Returns true on success or false on failure.
     */
    public function start(): bool;

    /**
     * Stop a session and destroys all data.
     *
     * @return bool Returns true on success or false on failure.
     */
    public function stop(): bool;

    /**
     * Check to see if a session already exists.
     *
     * @return bool Returns true if one exists and false if not.
     */
    public function exists(): bool;

    /**
     * Regenerates the session.
     *
     * @param bool $deleteOldSession Whether to delete the old session or not.
     *
     * @return bool Returns true on success or false on failure.
     */
    public function regenerate(bool $deleteOldSession = true): bool;

    /**
     * Checks whether a value for the specified key exists in the session.
     *
     * @param string $key The key to check.
     *
     * @return bool Returns true if the key was found and false if not.
     */
    public function has(string $key): bool;

    /**
     * Returns the requested value from the session or, if not found, the
     * specified default value.
     *
     * @param string $key          The key to retrieve the value for.
     * @param mixed  $defaultValue The default value to return if the
     *                             requested value cannot be found.
     *
     * @return mixed Returns the requested value or the default
     *               value.
     */
    public function get(string $key, $defaultValue = \null);

    /**
     * Returns the requested value and removes it from the session.
     *
     * This is identical to calling `get` first and then `remove` for the same
     * key.
     *
     * @param string $key         The key to retrieve and remove the value for.
     * @param mixed $defaultValue The default value to return if the requested
     *                            value cannot be found.
     *
     * @return mixed The requested value or the default value.
     */
    public function flash(string $key, $defaultValue = \null);

    /**
     * Sets the value for the specified key to the given value.
     *
     * Any data that already exists for the specified key will be overwritten.
     *
     * @param string $key   The key to set the value for.
     * @param mixed  $value The value to set.
     *
     * @return void Returns nothing.
     */
    public function put(string $key, $value): void;

    /**
     * Removes the value for the specified key from the session.
     *
     * @param string $key The key to remove the value for.
     *
     * @return void Returns nothing.
     */
    public function delete(string $key): void;
}
