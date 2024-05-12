<?php

namespace App\Request;

use Jawira\CaseConverter\Convert;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class AbstractRequest
{
    public function __construct(protected ValidatorInterface $validator, protected RequestStack $requestStack)
    {
        $this->populate();
        $this->validate();
    }

    public function getRequest(): Request
    {
        return $this->requestStack->getCurrentRequest();
    }

    protected function populate(): void
    {
        $request = $this->getRequest();
        $reflection = new ReflectionClass($this);

        foreach ($request->toArray() as $property => $value) {
            $attribute = self::camelCase($property);
            if (property_exists($this, $attribute)) {
                try {
                    $reflectionProperty = $reflection->getProperty($attribute);
                    $reflectionProperty->setValue($this, $value);
                } catch (ReflectionException) {
                }
            }
        }
    }

    protected function validate(): void
    {
        $violations = $this->validator->validate($this);
        if (count($violations) < 1) {
            return;
        }

        $errors = [];

        /** @var ConstraintViolation $violation */
        foreach ($violations as $violation) {
            $attribute = self::snakeCase($violation->getPropertyPath());
            $errors[] = [
                'property' => $attribute,
                'value' => $violation->getInvalidValue(),
                'message' => $violation->getMessage(),
            ];
        }
        $headers = ["Access-Control-Allow-Origin" => '*'];
        $response = new JsonResponse(['success' => false, 'errors' => $errors], 400, $headers);
        $response->send();
        exit;
    }

    private static function camelCase(string $attribute): string
    {
        return (new Convert($attribute))->toCamel();
    }

    private static function snakeCase(string $attribute): string
    {
        return (new Convert($attribute))->toSnake();
    }
}