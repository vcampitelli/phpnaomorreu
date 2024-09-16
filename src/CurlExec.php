<?php

declare(strict_types=1);

namespace App;

use DOMDocument;
use DOMXPath;
use RuntimeException;

class CurlExec
{
    public function fetchAsString(string $url): string
    {
        $curlHandle = \curl_init();
        \curl_setopt_array($curlHandle, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT => 'vcampitelli',
        ]);

        $response = \curl_exec($curlHandle);
        if ($response === false) {
            throw new RuntimeException(\curl_error($curlHandle));
        }

        return $response;
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
