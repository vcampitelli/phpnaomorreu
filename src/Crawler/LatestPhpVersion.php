<?php

declare(strict_types=1);

namespace App\Crawler;

use App\CurlExec;
use DateTimeImmutable;

class LatestPhpVersion
{
    public function __construct(private readonly string $url)
    {
    }

    public function __invoke(): ?array
    {
        $json = (new CurlExec())->fetchAsString($this->url);
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
