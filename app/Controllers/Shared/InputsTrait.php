<?php

/**
 * @noinspection PhpPossiblePolymorphicInvocationInspection
 * @noinspection PhpMultipleClassDeclarationsInspection
 */

declare(strict_types=1);

namespace App\Controllers\Shared;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\UploadedFileInterface;
use RuntimeException;
use Slim\Psr7\UploadedFile;
use Stringable;

trait InputsTrait
{
    /** @var array<mixed> */
    private array $inputs = [];

    /** @param string[] $jsonDecodeBase64 */
    private function setUpInputs(Request $request, array $jsonDecodeBase64): void
    {
        $this->inputs = $this->parsedBodyFromRequest($request, $jsonDecodeBase64);
    }

    /**
     * @param Request $request
     * @param string[] $jsonDecodeBase64
     * @return array<mixed>
     */
    private function parsedBodyFromRequest(Request $request, array $jsonDecodeBase64): array
    {
        $parsedBody = (array) ($request->getParsedBody() ?? []);
        foreach ($parsedBody as $key => $value) {
            if (in_array($key, $jsonDecodeBase64, true) && is_string($value)) {
                $parsedBody[$key] = base64_decode($value);
            }
        }
        return array_merge(
            $parsedBody,
            $this->uploadedFilesToInputs($request->getUploadedFiles()),
        );
    }

    private function getString(string $key): string
    {
        if (! isset($this->inputs[$key])) {
            return '';
        }

        return $this->toString($this->inputs[$key]);
    }

    /**
     * @return array<string, string>
     */
    private function getStrings(string $key): array
    {
        if (! isset($this->inputs[$key])) {
            return [];
        }

        $values = $this->inputs[$key];
        if (! is_array($values)) {
            return [];
        }

        foreach ($values as $key => $value) {
            $values[$key] = $this->toString($value);
        }
        return $values;
    }

    private function toString(mixed $value): string
    {
        if (null === $value || is_scalar($value) || ($value instanceof Stringable)) {
            return strval($value);
        }

        return '';
    }

    /**
     * @param array<string, UploadedFileInterface> $uploadedFiles
     * @return array<string, non-empty-string>
     */
    private function uploadedFilesToInputs(array $uploadedFiles): array
    {
        $inputs = [];
        foreach ($uploadedFiles as $key => $uploadedFile) {
            $inputs[$key] = $this->uploadedFileToString($uploadedFile);
        }
        return array_filter($inputs, fn (string $value): bool => '' !== $value);
    }

    private function uploadedFileToString(UploadedFileInterface $uploadedFile): string
    {
        try {
            if (UPLOAD_ERR_OK !== $uploadedFile->getError()) {
                return '';
            }
            return (string) $uploadedFile->getStream();
        } catch (RuntimeException) {
            return '';
        } finally {
            if ($uploadedFile instanceof UploadedFile) {
                unlink($uploadedFile->getFilePath());
            }
        }
    }
}
