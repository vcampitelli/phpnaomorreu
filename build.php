<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

$run = function (string $description, callable $execute): mixed {
    try {
        echo "\e[0m[ \e[36m..\e[0m ] \e[36m{$description}\e[0m";
        $response = $execute();
        echo "\r\e[0m[ \e[1;32mOK\e[0m ] \e[36m{$description}\e[0m\n";
        return $response;
    } catch (\Throwable $t) {
        echo "\r\e[0m[ \e[1;31mERR\e[0m ] \e[36m{$description}\e[0m\n";
        echo "    \e[31m{$t->getMessage()}\e[0m\n";
        die(1);
    }
};

$stars = $run(
    'Estatísticas do GitHub',
    (new App\Crawler\GitHubApi(getenv('GITHUB_TOKEN')))
        ->addRepository('laravel', 'laravel/laravel')
        ->addRepository('slim', 'slimphp/Slim')
        ->addRepository('symfony', 'symfony/symfony')
        ->addRepository('composer', 'composer/composer')
        ->addRepository('awesomePhp', 'ziadoz/awesome-php')
        ->addOrganization('phpLeague', 'thephpleague')
        ->addOrganization('laminas', 'laminas')
);

[$latestPhpVersion, $latestPhpVersionReleaseDate] = $run(
    'Última versão do PHP',
    new App\Crawler\LatestPhpVersion(
        'https://www.php.net/releases/active.php',
    )
);

$phpUsagePercentage = $run(
    'Uso do PHP no w3techs.com',
    new App\Crawler\PhpUsagePercentage(
        'https://w3techs.com/technologies/history_overview/programming_language',
    )
);

[$wordpressCmsUsagePercentage, $wordpressTotalUsagePercentage] = $run(
    'Uso do WordPress no w3techs.com',
    new App\Crawler\WordpressUsagePercentage(
        'https://w3techs.com/technologies/details/cm-wordpress',
    )
);

ob_start();
require __DIR__ . '/template/index.phtml';
$template = ob_get_clean();

file_put_contents(__DIR__ . '/public/index.html', $template, LOCK_EX);
