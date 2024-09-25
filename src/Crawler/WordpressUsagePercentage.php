<?php

declare(strict_types=1);

namespace App\Crawler;

use App\CurlExec;
use DOMDocument;
use DOMXPath;

readonly class WordpressUsagePercentage
{
    public function __construct(
        private string $url,
        private CurlExec $curlExec
    ) {
    }

    public function __invoke(): ?array
    {
        $xpath = $this->curlExec->fetchAsXpath($this->url);

        $languages = $xpath->query('//p[@class="surv"]');
        foreach ($languages as $language) {
            // @FIXME
            preg_match(
                '/WordPress is used by ([0-9]+\.[0-9]+)% of all the websites whose content management system ' .
                'we know. This is ([0-9]+\.[0-9]+)% of all websites./',
                $language->textContent,
                $matches
            );
            return [
                (float) $matches[1],
                (float) $matches[2],
            ];
        }

        return null;
    }
}
