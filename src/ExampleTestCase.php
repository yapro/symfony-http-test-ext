<?php

declare(strict_types=1);

namespace YaPro\SymfonyHttpTestExt;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\BrowserKitAssertionsTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\AbstractBrowser;
use YaPro\Helper\JsonHelper;
use YaPro\SymfonyHttpClientExt\HttpClientJsonExtTrait;

// it is very important : the class with the getHttpClient method
class ExampleTestCase extends WebTestCase
{
    use AssertionsTrait;
    use ExtTrait;
    use BrowserKitAssertionsTrait;
    use HttpClientJsonExtTrait;

    /**
     * @var KernelBrowser|AbstractBrowser|null
     */
    protected static KernelBrowser $client;
    private static JsonHelper $jsonHelper;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        if (static::$booted === false) {
            self::$client = self::getClient(self::createClient());
        }

        self::$jsonHelper = new JsonHelper();
    }

    // it is very important to make the getHttpClient method, because it is used in HttpClientTrait
    public function getHttpClient()
    {
        return self::$client;
    }

    // it is very important to make the getJsonHelper method (if you wish to use HttpClientJsonExtTrait)
    protected function getJsonHelper(): JsonHelper
    {
        return self::$jsonHelper;
    }
}
