<?php
declare(strict_types=1);
/**
 * Kooser Session - Securely manage and preserve session data.
 * 
 * @package Kooser\Session.
 */

namespace Kooser\Session\Middleware;

/**
 * Slim Framework Integration.
 */
class Slim
{
    /** @var array $options The session config options. */
    private $options = [];

    /**
     * Construct this middleware.
     *
     * @param array $options The session config options.
     *
     * @return void Returns nothing.
     */
    public function __construct(array $options)
    {
        $this->options = $options;
    }

    /**
     * Example middleware invokable class
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface      $response PSR7 response
     * @param  callable                                 $next     Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke($request, $response, $next)
    {
        \Kooser\Session\SessionManager::start($this->options);
        $response = $next($request, $response);
        return $response;
    }
}
