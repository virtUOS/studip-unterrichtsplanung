<?php

namespace Unterrichtsplanung;

Trait UnterrichtsplanungTrait
{
    public function getRequestData($request)
    {
        $body = (string) $request->getBody();
        if ('' === $body) {
            throw new UnprocessableEntityException('Empty request');
        }
        $result = json_decode($body, true);
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new UnprocessableEntityException(json_last_error_msg());
        }

        return $result;
    }

    public function createResponse($data, $response)
    {
        return $response->withHeader(
            'Content-Type',
            'application/vnd.api+json'
        )
        ->write(json_encode($data));
    }
}
