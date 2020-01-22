<?php

namespace Unterrichtsplanung\Routes\Schedules;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Unterrichtsplanung\Errors\AuthorizationFailedException;
use Unterrichtsplanung\Errors\Error;
use Unterrichtsplanung\UnterrichtsplanungTrait;
use Unterrichtsplanung\UnterrichtsplanungController;
use Unterrichtsplanung\Models\Schedules;

class SchedulesUpdate extends UnterrichtsplanungController
{
    use UnterrichtsplanungTrait;

    public function __invoke(Request $request, Response $response, $args)
    {
        global $user;

        $json = $this->getRequestData($request, ['content', 'plans_id']);

        $schedules = Schedules::find($args['id']);

        if ($schedules) {
            if ($schedules->plans->user_id != $user->id) {
                throw new Error('Access denied!', 403);
            }

            $schedules->setData([
                'content'       => $json['content'],
                'plans_id'      => $json['plans_id']
            ]);

            $schedules->store();

            return $this->createResponse($schedules->toArray(), $response);
        } else {
            throw new Error('Schedule not found', 404);
        }
    }
}
