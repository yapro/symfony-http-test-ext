<?php

namespace YaPro\SymfonyHttpTestExt;

use InvalidArgumentException;

trait AssertionsTrait
{
    protected int $httpCodeCreated = 201;
    protected int $httpCodeNoContent = 204;
    protected int $httpCodeBadRequest = 400;
    protected int $httpCodeForbidden = 403;

    protected function assertResourceIsCreatedOrUpdated(int $id = 0): int
    {
        $response = $this->getResponseAsArray();
        self::assertTrue(isset($response['id']));
        if (is_numeric($response['id']) && $response['id'] > 0) {
            $resultId = filter_var($response['id'], FILTER_VALIDATE_INT);
        } elseif (is_string($response['id']) && trim($response['id']) !== '') {
            $resultId = $response['id'];
        } else {
            self::assertSame('', 'id has wrong type');
            return $id;
        }
        if ($id) {
            self::assertSame($id, $resultId);
        }

        return $resultId;
    }

    protected function assertResourceIsCreated(string $message = ''): int
    {
        self::assertSame($this->httpCodeCreated, self::$client->getResponse()->getStatusCode(), $message);

        return $this->assertResourceIsCreatedOrUpdated();
    }

    protected function assertResourceIsUpdated(int $id = 0, string $message = ''): int
    {
        self::assertResponseIsSuccessful($message);

        return $this->assertResourceIsCreatedOrUpdated($id);
    }

    protected function assertResourceIsDeleted(string $message = ''): void
    {
        self::assertSame($this->httpCodeNoContent, self::$client->getResponse()->getStatusCode(), $message);
    }

    protected function assertResponseSayBadRequest(string $message = ''): void
    {
        self::assertSame($this->httpCodeBadRequest, self::$client->getResponse()->getStatusCode(), $message);
    }

    protected function assertResponseSayAccessDenied(string $message = ''): void
    {
        self::assertSame($this->httpCodeForbidden, $this->responseHttpCode, $message);
    }

    protected function assertJsonResponse($json)
    {
        // удаляем переносы строк и пробелы между именами полей и значениями, но не в значениях
        $jsonAsArray = $this->getJsonHelper()->jsonDecode($json, true);
        $this->assertSame($jsonAsArray, $this->getResponseAsArray());
    }

    /**
     * Asserts that the retrieved JSON contains the specified subset.
     *
     * This method delegates to static::assertArraySubset().
     *
     * @param array|string $subset
     * @param bool $checkForObjectIdentity
     * @param string $message
     */
//    protected static function assertJsonContains($subset, bool $checkForObjectIdentity = true, string $message = ''): void
//    {
//        if (\is_string($subset)) {
//            $subset = json_decode($subset, true, 123, JSON_THROW_ON_ERROR);
//        }
//        if (!\is_array($subset)) {
//            throw new InvalidArgumentException('Subset must be array or string (JSON array or JSON object)');
//        }
//
//        $actual = json_decode(self::$client->getResponse()->getContent(), true, 123, JSON_THROW_ON_ERROR);
//
//        static::assertArraySubset($subset, $actual, $checkForObjectIdentity, $message);
//    }

    /**
     * Asserts that the retrieved JSON is equal to $json.
     *
     * Both values are canonicalized before the comparison.
     *
     * @param array|string $json
     * @param string $message
     */
    protected function assertJsonEquals($json, string $message = ''): void
    {
        if (\is_string($json)) {
            $json = json_decode($json, true, 123, JSON_THROW_ON_ERROR);
        }
        if (!\is_array($json)) {
            throw new InvalidArgumentException('Json must be array or string (JSON array or JSON object)');
        }

        static::assertEqualsCanonicalizing($json, self::$client->getResponse()->toArray(false), $message);
    }

    /**
     * Asserts that an array has a specified subset.
     *
     * Imported from dms/phpunit-arraysubset, because the original constraint has been deprecated.
     *
     * @param iterable $subset
     * @param iterable $array
     * @param bool $checkForObjectIdentity
     * @param string $message
     *
     * @copyright Rafael Dohms <rdohms@gmail.com>
     *
     * @see https://github.com/sebastianbergmann/phpunit/issues/3494
     *
     * @copyright Sebastian Bergmann <sebastian@phpunit.de>
     */
//    protected static function assertArraySubset($subset, $array, bool $checkForObjectIdentity = false, string $message = ''): void
//    {
//        $class = 'ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Constraint\ArraySubset';
//        if (!class_exists($class)) {
//            throw new UnexpectedValueException('You need to install ApiPlatform');
//        }
//        $constraint = new $class($subset, $checkForObjectIdentity);
//
//        static::assertThat($array, $constraint, $message);
//    }
}
