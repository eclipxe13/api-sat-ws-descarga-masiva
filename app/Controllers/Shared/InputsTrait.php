<?php

/**
 * @noinspection PhpPossiblePolymorphicInvocationInspection
 * @noinspection PhpMultipleClassDeclarationsInspection
 */

declare(strict_types=1);

namespace App\Controllers\Shared;

use PhpCfdi\SatWsDescargaMasiva\Shared\DateTime;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\UploadedFileInterface;
use RuntimeException;
use Slim\Psr7\UploadedFile;
use Stringable;

trait InputsTrait
{
    /** @var array<mixed> */
    private array $inputs = [];

    private function setUpInputsFromRequest(Request $request): void
    {
        $inputs = array_merge(
            (array) ($request->getParsedBody() ?? []),
            $this->uploadedFilesToInputs($request->getUploadedFiles()),
        );
        $this->setUpInputs($inputs);
    }

    /** @param array<mixed> $inputs */
    private function setUpInputs(array $inputs): void
    {
        $this->inputs = $inputs;
    }

    private function getString(string $key, bool $allowBase64 = false): string
    {
        if (! isset($this->inputs[$key])) {
            return '';
        }

        $value = $this->toString($this->inputs[$key]);
        if ($allowBase64 && str_starts_with($value, 'base64:')) {
            $value = (string) base64_decode(substr($value, 7), true);
        }

        return $value;
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

    private function buildDateTimeFromValue(string $value): DateTime
    {
        if (is_numeric($value)) {
            $value = intval($value);
        }
        return DateTime::create($value);
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
