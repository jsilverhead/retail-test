<?php

namespace App\Infrastructure\Http\Payload;

use App\Infrastructure\Http\Payload\Exception\UnparsableRequestException;
use JsonException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

final class PayloadDeserializer
{
    public function deserialize(Request $request): Payload
    {
        $data = match ($request->getMethod()) {
            Request::METHOD_POST => $this->deserializeRequest($request),
            Request::METHOD_GET => $request->query->all(),
            default => throw new MethodNotAllowedHttpException(allow: [Request::METHOD_POST, Request::METHOD_GET]),
        };

        return new Payload($data);
    }

    public function deserializeRequest(Request $request): array
    {
        $payload = $request->getContent();

        if ('' === $payload) {
            return [];
        }

        try {
            $data = json_decode($payload, true, \JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            throw new UnparsableRequestException();
        }

        if (!\is_array($data)) {
            throw new UnparsableRequestException();
        }

        return $data;
    }
}
