<?php

declare(strict_types=1);

namespace Crawler;

use DOMDocument;
use DOMXPath;

class WordpressUsagePercentage
{
    public function __construct(private readonly string $url)
    {
    }

    public function __invoke(): ?array
    {
        $htmlString = file_get_contents($this->url);
        libxml_use_internal_errors(true);
        $doc = new DOMDocument();
        $doc->loadHTML($htmlString);
        $xpath = new DOMXPath($doc);

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
