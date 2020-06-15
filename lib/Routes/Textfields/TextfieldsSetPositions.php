<?php

namespace Unterrichtsplanung\Routes\Textfields;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Unterrichtsplanung\Errors\AuthorizationFailedException;
use Unterrichtsplanung\Errors\Error;
use Unterrichtsplanung\UnterrichtsplanungTrait;
use Unterrichtsplanung\UnterrichtsplanungController;
use Unterrichtsplanung\Models\Textfields;
use Unterrichtsplanung\Models\Plans;

class TextfieldsSetPositions extends UnterrichtsplanungController
{
    use UnterrichtsplanungTrait;

    public function __invoke(Request $request, Response $response, $args)
    {
        global $user;

        $json = $this->getRequestData($request, ['textfields', 'plans_id']);

        $plan = Plans::find($json['plans_id']);

        if ($plan->user_id != $user->id) {
            throw new Error('Access denied!', 403);
        }

        $ret = [];

        foreach ($json['textfields'] as $field)
        {
            $textfield = Textfields::find($field['id']);

            if ($textfield) {
                if ($textfield->plans_id == $json['plans_id']) {
                    $textfield->position = $field['position'];
                    $textfield->store();

                    $ret[] = $textfield->toArray();
                }
            }
        }

        return $this->createResponse($ret, $response);
    }
}
