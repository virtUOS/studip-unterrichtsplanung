<?php

namespace Unterrichtsplanung;

use Unterrichtsplanung\Errors\Error;

Trait UnterrichtsplanungTrait
{
    public function getRequestData($request, $required_fields = [])
    {
        $body = (string) $request->getBody();
        if ('' === $body) {
            throw new UnprocessableEntityException('Empty request');
        }
        $result = json_decode($body, true);
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new UnprocessableEntityException(json_last_error_msg());
        }

        foreach ($required_fields as $field) {
            if (!isset($result[$field])) {
                throw new Error('missing field in json-data: ' . $field, 422);
            }
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

    public function toArray($data)
    {
        $result = [];

        foreach ($data as $entry) {
            $result[] = $entry->toArray();
        }

        return $result;
    }
}
