<?php

declare(strict_types=1);

namespace App;

use DOMDocument;
use DOMXPath;
use RuntimeException;

class CurlExec
{
    private string $cachePath;
    private int $maxRetries = 3;
    private int $retryDelay = 5;



    public function __construct()
    {
        $this->cachePath = getenv('CACHE_PATH') ? getenv('CACHE_PATH') : null;

        if ($this->cachePath && !is_dir($this->cachePath)) {
            mkdir($this->cachePath, 0777, true);
        }
    }

    private function getCacheFilePath(string $url): string
    {
        $fileName = sha1($url) . '.html';
        return $this->cachePath . DIRECTORY_SEPARATOR . $fileName;   
    }

    public function fetchAsString(string $url): string
    {
        $cacheFile = $this->getCacheFilePath($url);

        if ($this->cachePath && file_exists($cacheFile)) {
            echo " - Retornando resposta do cache para URL: {$url}\n";
            return file_get_contents($cacheFile);
        }

        $attempt = 0;

        while ($attempt < $this->maxRetries) {
            try {
                echo " - Realizando requisição para URL: {$url} (tentativa ". ($attempt + 1) . ")\n";

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
                        $attempt++;
                        echo "Timeout❗. Tentando novamente em {$this->retryDelay} segundos...\n";
                        sleep($this->retryDelay);
                        continue;
                    }
                    throw new RuntimeException(\curl_error($curlHandle));
                }

                
                if ($this->cachePath) {
                    file_put_contents($cacheFile, $response);
                }

                return $response;
            } catch (RuntimeException $e) {
                throw $e;
            }
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
}
