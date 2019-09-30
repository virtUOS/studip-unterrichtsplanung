<?php

namespace Unterrichtsplanung\Middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Unterrichtsplanung\Errors\Error;

class AdminPerms
{
    /**
     * Checks, if the current user has the admin role
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request  das
     *                                                           PSR-7 Request-Objekt
     * @param \Psr\Http\Message\ResponseInterface      $response das PSR-7
     *                                                           Response-Objekt
     * @param callable                                 $next     das nächste Middleware-Callable
     *
     * @return \Psr\Http\Message\ResponseInterface das neue Response-Objekt
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public function __invoke(Request $request, Response $response, $next)
    {
        $container = $this->container;

        if (!\RolePersistence::isAssignedRole($GLOBALS['user']->user_id,
                $container['role']['admin']))
        {
            //throw new Error('Access Denied', 403);
        }

        return $next($request, $response);

    }
}
