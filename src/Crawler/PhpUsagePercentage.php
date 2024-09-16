<?php

declare(strict_types=1);

namespace App\Crawler;

use App\CurlExec;
use DOMDocument;
use DOMXPath;

class PhpUsagePercentage
{
    public function __construct(private readonly string $url)
    {
    }

    public function __invoke(): ?float
    {
        $xpath = (new CurlExec())->fetchAsXpath($this->url);

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
