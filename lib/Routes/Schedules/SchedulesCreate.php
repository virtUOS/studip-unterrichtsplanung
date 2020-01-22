<?php

namespace Unterrichtsplanung\Routes\Schedules;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Unterrichtsplanung\Errors\AuthorizationFailedException;
use Unterrichtsplanung\Errors\Error;
use Unterrichtsplanung\UnterrichtsplanungTrait;
use Unterrichtsplanung\UnterrichtsplanungController;
use Unterrichtsplanung\Models\Schedules;
use Unterrichtsplanung\Models\Plans;

class SchedulesCreate extends UnterrichtsplanungController
{
    use UnterrichtsplanungTrait;

    public function __invoke(Request $request, Response $response, $args)
    {
        global $user;

        $json = $this->getRequestData($request, ['content', 'plans_id']);

        $plan = Plans::find($json['plans_id']);

        if ($plan->user_id != $user->id) {
                throw new Error('Access denied!', 403);
        }

        $schedules = Schedules::create([
            'content'       => $json['content'],
            'plans_id'      => $json['plans_id']
        ]);

        return $this->createResponse($schedules->toArray(), $response);
    }
}
