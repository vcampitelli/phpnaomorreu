<?php

declare(strict_types=1);

namespace App;

use DOMDocument;
use DOMXPath;
use RuntimeException;

class CurlExec
{
    /*
        # Algoritmo

        variavel arrayCache = [];

        se (arrayCache.TemValor) {
            retorna Valor do arrayCache;
        }
        
        variável resposta = realizarRequisição(url);

        arrayCache = resposta;

        retorna $resposta;
    */ 

    //Mantem o cache em memoria com static
    private static array $cache = [];

    public function fetchAsString(string $url): string
    {
        //Verifica se ja temos a resposta no cache
        if(isset(self::$cache[$url])) {
            echo "Retornando resposta do cache para URL: {$url}\n";
            return self::$cache[$url];
        }

        echo "Realizando requisição para URL: {$url}\n";

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

        self::$cache[$url] = $response;
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
