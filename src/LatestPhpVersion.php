<?php

declare(strict_types=1);

namespace Crawler;

use DateTimeImmutable;

class LatestPhpVersion
{
    public function __construct(private readonly string $url)
    {
    }

    public function __invoke(): ?array
    {
        $json = file_get_contents($this->url);
        $json = json_decode($json, true);
        $latestMajor = end($json);
        $latestMinor = end($latestMajor);
        $date = DateTimeImmutable::createFromFormat('d M Y', $latestMinor['date']);

        return [
            $latestMinor['version'],
            $date,
        ];
    }
}
