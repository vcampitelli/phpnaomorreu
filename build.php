<?php

declare(strict_types=1);

require __DIR__ . '/src/GitHubApi.php';
require __DIR__ . '/src/LatestPhpVersion.php';
require __DIR__ . '/src/PhpUsagePercentage.php';
require __DIR__ . '/src/WordpressUsagePercentage.php';

$stars = (new Crawler\GitHubApi(getenv('GITHUB_TOKEN')))
    ->addRepository('laravel', 'laravel/laravel')
    ->addRepository('slim', 'slimphp/Slim')
    ->addRepository('symfony', 'symfony/symfony')
    ->addRepository('composer', 'composer/composer')
    ->addRepository('awesomePhp', 'ziadoz/awesome-php')
    ->addOrganization('phpLeague', 'thephpleague')
    ->addOrganization('laminas', 'laminas')
    ->run();

[$latestPhpVersion, $latestPhpVersionReleaseDate] = (new Crawler\LatestPhpVersion(
    'https://www.php.net/releases/active.php',
))();
$phpUsagePercentage = (new Crawler\PhpUsagePercentage(
    'https://w3techs.com/technologies/history_overview/programming_language',
))();
[$wordpressCmsUsagePercentage, $wordpressTotalUsagePercentage] = (new Crawler\WordpressUsagePercentage(
    'https://w3techs.com/technologies/details/cm-wordpress',
))();

ob_start();
require __DIR__ . '/template/index.phtml';
$template = ob_get_clean();

file_put_contents(__DIR__ . '/public/index.html', $template, LOCK_EX);
