<?php

namespace Unterrichtsplanung\Middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class Authentication
{
    // der Schlüssel des Request-Attributs, in dem der Stud.IP-Nutzer
    // gefunden werden kann:

    // $user = $request->getAttribute(Authentication::USER_KEY);
    const USER_KEY = 'studip-user';

    /**
     * Hier muss die Autorisierung implementiert werden.
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
        if (!is_null($this->user())) {
            $request = $this->provideUser($request, $this->user());

            return $next($request, $response);
        } else {
            throw new \AccessDeniedException();
        }

        return $response;
    }

    public function user()
    {
        if (!is_null($this->user)) {
            return $this->user;
        }

        $isAuthenticated = isset($GLOBALS['auth']) && $GLOBALS['auth']->is_authenticated() && 'nobody' !== $GLOBALS['user']->id;

        if ($isAuthenticated) {
            $this->user = $GLOBALS['user']->getAuthenticatedUser();
        }

        return $this->user;
    }

    /**
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    private function provideUser(Request $request, \User $user)
    {
        if ('nobody' === $GLOBALS['user']->id) {
            $GLOBALS['user'] = new \Seminar_User($user->id);
            $GLOBALS['auth'] = new \Seminar_Auth();
            $GLOBALS['auth']->auth = [
                'uid' => $user->id,
                'uname' => $user->username,
                'perm' => $user->perms,
            ];
            $GLOBALS['perm'] = new \Seminar_Perm();
            $GLOBALS['MAIL_VALIDATE_BOX'] = false;
            $GLOBALS['sess']->delete();
            setTempLanguage($user->id);
        }

        return $request->withAttribute(self::USER_KEY, $user);
    }
}
