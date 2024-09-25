<?php

declare(strict_types=1);

namespace App\Crawler;

use App\CurlExec;
use DateTimeImmutable;

readonly class LatestPhpVersion
{
    public function __construct(
        private string $url,
        private CurlExec $curlExec,
    ) {
    }

    public function __invoke(): ?array
    {
        $json = $this->curlExec->fetchAsString($this->url);
        $json = \json_decode($json, true);
        $latestMajor = end($json);
        $latestMinor = end($latestMajor);
        $date = DateTimeImmutable::createFromFormat('d M Y', $latestMinor['date']);

        return [
            $latestMinor['version'],
            $date,
        ];
    }
}
