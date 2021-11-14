<?php

declare(strict_types=1);

namespace YaPro\SymfonyHttpTestExt;

use YaPro\SymfonyRequestToCurlCommand\SymfonyRequestToCurlCommandConverter;
use function fwrite;
use function implode;
use function json_decode;
use function print_r;
use const PHP_EOL;
use const STDOUT;

trait ExtTrait
{
    protected function tearDown(): void
    {
        if ($this->getStatus() === 0) {
            return;
        }
        $this->writeLn('Test: ' . $this->getName());
        $this->showCurlRequest();
        $this->showResponse();
    }

    protected function showCurlRequest(): void
    {
        $curlRequest = (new SymfonyRequestToCurlCommandConverter())->convert($this->getHttpClient()->getRequest());
        $this->writeLn('CurlRequest: ' . $curlRequest);
    }

    protected function showResponse(): void
    {
        $this->writeLn('Response headers: ' . print_r($this->getHeadersAsFlatArray(), true));
        $this->writeLn('Response content: ' . $this->getHttpClient()->getResponse()->getContent());
    }

    protected function writeLn(string $text): int
    {
        return fwrite(STDOUT, PHP_EOL . $text . PHP_EOL);
    }

    protected function getHeadersAsFlatArray(): array
    {
        $result = [];
        foreach ($this->getHttpClient()->getResponse()->getHeaders() as $headerName => $headerValue) {
            $result[$headerName] = implode(';', $headerValue);
        }

        return $result;
    }

    protected function showResponseStatusCode(): void
    {
        $this->writeLn('ResponseStatusCode: ' . $this->getHttpClient()->getResponse()->getStatusCode());
    }

    protected function getResponseAsArray(): array
    {
        return json_decode($this->getHttpClient()->getResponse()->getContent(), true);
    }

    protected function getHeader(string $name): ?string
    {
        return $this->getHttpClient()->getResponse()->headers->get($name);
    }
}
