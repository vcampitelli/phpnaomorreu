<?php

declare(strict_types=1);

namespace App;

use DOMDocument;
use DOMXPath;
use RuntimeException;

class CurlExec
{
    //variável de cache
    private string $cachePath;

    public function __construct()
    {
        //Obtem o caminho do cache a partir da variável de ambiente
        $this->cachePath = getenv('CACHE_PATH') ? getenv('CACHE_PATH') : null;

        //se a variavel esta definida e o caminho não existe ele cria
        if ($this->cachePath && !is_dir($this->cachePath)) {
            mkdir($this->cachePath, 0777, true);
        }
    }

    private function getCacheFilePath(string $url): string
    {
        //Gera o nome do arq
        //Usando sha1
        $fileName = sha1($url) . '.html';

        //Retorna o caminho do arquivo
        return $this->cachePath . DIRECTORY_SEPARATOR . $fileName;   
    }

    public function fetchAsString(string $url): string
    {
        $cacheFile = $this->getCacheFilePath($url);

        //Se o cache esta habilitado e o arq de cache existe, retorna o cache
        if ($this->cachePath && file_exists($cacheFile)) {
            echo " - Retornando resposta do cache para URL: {$url}\n";
            return file_get_contents($cacheFile);
        }

        //Caso não tenha cache
        echo " - Realizando requisição para URL: {$url}\n";

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

        if ($this->cachePath) {
            file_put_contents($cacheFile, $response);
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
