<?php

declare(strict_types=1);

namespace App\Crawler;

use CurlHandle;
use RuntimeException;
use stdClass;

class GitHubApi
{
    /**
     * @var CurlHandle[]
     */
    private array $handlers = [];

    /**
     * @var bool[]
     */
    private array $isOrganization = [];

    public function __construct(private readonly string $token)
    {
    }

    public function addRepository(string $entry, string $repository): self
    {
        $this->handlers[$entry] = $this->fetchByRepository($repository);
        return $this;
    }

    public function addOrganization(string $entry, string $organization): self
    {
        $this->handlers[$entry] = $this->fetchByOrganization($organization);
        $this->isOrganization[$entry] = true;
        return $this;
    }

    public function __invoke(): stdClass
    {
        $curlMultiHandler = \curl_multi_init();

        foreach ($this->handlers as $handler) {
            \curl_multi_add_handle($curlMultiHandler, $handler);
        }

        $active = true;
        do {
            $status = \curl_multi_exec($curlMultiHandler, $active);
            if ($active) {
                \curl_multi_select($curlMultiHandler);
            }
        } while ($active && $status == CURLM_OK);

        foreach ($this->handlers as $handler) {
            \curl_multi_remove_handle($curlMultiHandler, $handler);
        }
        \curl_multi_close($curlMultiHandler);

        $response = new stdClass();
        foreach ($this->handlers as $entry => $handler) {
            $json = \curl_multi_getcontent($handler);

            $response->$entry = $this->parseJson($entry, $json);
        }

        return $response;
    }

    private function parseJson(string $entry, string $json): string
    {
        if (empty($json)) {
            throw new RuntimeException("Erro ao buscar dados de {$entry}: resposta vazia");
        }
        $json = \json_decode($json);
        if (empty($json)) {
            throw new RuntimeException("Erro ao buscar dados de {$entry}: JSON inválido");
        }

        if (empty($this->isOrganization[$entry])) {
            return ($json->stargazers_count) ? $this->shortNumber($json->stargazers_count) : '';
        }

        $stars = 0;
        foreach ($json as $repo) {
            if (!empty($repo->stargazers_count)) {
                $stars += $repo->stargazers_count;
            }
        }
        return $this->shortNumber($stars);
    }

    private function fetchByRepository(string $repository): CurlHandle
    {
        return $this->makeRequest("https://api.github.com/repos/{$repository}");
    }

    private function fetchByOrganization(string $organization): CurlHandle
    {
        return $this->makeRequest("https://api.github.com/orgs/{$organization}/repos");
    }

    private function makeRequest(string $url): CurlHandle
    {
        $curlHandle = \curl_init();
        \curl_setopt_array($curlHandle, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT => 'vcampitelli',
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer {$this->token}",
            ],
        ]);
        return $curlHandle;
    }

    /**
     * @param int $num
     * @return string
     * @link https://stackoverflow.com/a/52490452
     */
    private function shortNumber(int $num): string
    {
        $units = ['', 'K', 'M', 'B', 'T'];
        for ($i = 0; $num >= 1000; $i++) {
            $num /= 1000;
        }
        return \number_format($num, 1, ',', '.') . $units[$i];
    }
}
