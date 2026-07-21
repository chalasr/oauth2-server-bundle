<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php82\Rector\Class_\ReadOnlyClassRector;
use Rector\Symfony\Symfony80\Rector\Class_\RemoveEraseCredentialsRector;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/config',
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->withPhpSets()
    ->withPreparedSets(typeDeclarations: true, deadCode: true)
    ->withComposerBased(symfony: true, phpunit: true, doctrine: true)
    // Rules newly triggered by the 2.0 PHP 8.2 / Symfony 7.4 floor bump are skipped for now:
    //  - ReadOnlyClassRector would introduce BC breaks (readonly classes on value objects/models).
    //  - RemoveEraseCredentialsRector / RemoveEmptyClassMethodRector would drop public methods that
    //    must stay while Symfony 7.4 is supported (e.g. ClientCredentialsUser::eraseCredentials()).
    //    RemoveEraseCredentialsRector only registers on Symfony >= 8.0, so the refactoring CI (which
    //    runs on the 7.4 floor) reports it as "never registered" — that note is harmless (exit 0).
    ->withSkip([
        ReadOnlyClassRector::class,
        RemoveEraseCredentialsRector::class,
    ])
;
