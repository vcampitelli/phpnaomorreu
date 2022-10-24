<?php

declare(strict_types=1);

namespace Crawler;

use DOMDocument;
use DOMXPath;

class PhpUsagePercentage
{
    public function __construct(private readonly string $url)
    {
    }

    public function __invoke(): ?float
    {
        $htmlString = file_get_contents($this->url);
        libxml_use_internal_errors(true);
        $doc = new DOMDocument();
        $doc->loadHTML($htmlString);
        $xpath = new DOMXPath($doc);

        $languages = $xpath->query('//table[@class="hist"]/tr');
        foreach ($languages as $language) {
            if ($language->firstChild->textContent === 'PHP') {
                $percentage = $language->lastChild->textContent;
                if ($percentage[-1] === '%') {
                    return (float) $percentage;
                }
                break;
            }
        }

        return null;
    }
}
