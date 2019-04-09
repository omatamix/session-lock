<?php
declare(strict_types=1);
/**
 * Kooser Session - Securely manage and preserve session data.
 * 
 * @package Kooser\Session.
 */

namespace Kooser\Session;

/**
 * Secure session management for Slim.
 *
 * This middleware ensures a secure session is running at all times.
 *
 * @class SlimFrameworkMiddleware.
 */
class SlimFrameworkMiddleware
{

    /** @var array $settings The session settings for Slim. */
    private $settings = [];

    /**
     * Construct the Slim middleware.
     *
     * @return void Returns nothing.
     */
    public function __construct(array $settings)
    {
        $this->settings = $settings;
    }

    /**
     * Kooser Session middleware invokable class
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface      $response PSR7 response
     * @param  callable                                 $next     Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke($request, $response, $next)
    {
        if (!SessionManager::exists()) {
            SessionManager::start($this->settings);
        }
        $response = $next($request, $response);
        return $response;
    }
}
