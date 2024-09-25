<?php

declare(strict_types=1);

namespace App;

use DOMDocument;
use DOMXPath;
use RuntimeException;

readonly class CurlExec
{
    public function __construct(
        private ?string $cachePath = null,
        private int $maxRetries = 3,
        private int $retryDelay = 5,
    ) {
        if (($cachePath) && (!\is_dir($cachePath))) {
            \mkdir($cachePath, 0755, true);
        }
    }

    public function fetchAsString(string $url): string
    {
        if ($this->cachePath) {
            $cacheFile = $this->getCacheFilePath($url);
            if (\file_exists($cacheFile)) {
                return \file_get_contents($cacheFile);
            }
        }

        $attempt = 0;
        while ($attempt < $this->maxRetries) {
            $response = $this->makeRequest($url);

            // Timeout
            if ($response === false) {
                \sleep($this->retryDelay);
                $attempt++;
                continue;
            }

            // Resposta verdadeira
            if (isset($cacheFile)) {
                \file_put_contents($cacheFile, $response);
            }
            return $response;
        }

        throw new RuntimeException("Falha ao realizar a requisição para URL: {$url}");
    }

    public function fetchAsXpath(string $url): DOMXPath
    {
        $htmlString = $this->fetchAsString($url);
        \libxml_use_internal_errors(true);
        $doc = new DOMDocument();
        $doc->loadHTML($htmlString);
        return new DOMXPath($doc);
    }

    protected function makeRequest(string $url): string|false
    {
        $curlHandle = \curl_init();
        \curl_setopt_array($curlHandle, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT => 'vcampitelli',
        ]);

        $response = \curl_exec($curlHandle);

        if ($response === false) {
            $errorCode = \curl_errno($curlHandle);
            if ($errorCode === CURLE_OPERATION_TIMEDOUT) {
                return false;
            }
            throw new RuntimeException(\curl_error($curlHandle));
        }

        return $response;
    }

    private function getCacheFilePath(string $url): string
    {
        $fileName = \sha1($url) . '.html';
        return $this->cachePath . DIRECTORY_SEPARATOR . $fileName;
    }
}
