<?php

declare(strict_types=1);

use phpDocumentor\Guides\RestructuredText\Directives\BaseDirective;
use phpDocumentor\Guides\RestructuredText\Directives\SubDirective;
use phpDocumentor\Guides\RestructuredText\Parser\Productions\DirectiveContentRule;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use T3Docs\GuidesPhpDomain\Directives\Php\ConstDirective;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

use T3Docs\GuidesPhpDomain\Directives\Php\EnumDirective;
use T3Docs\GuidesPhpDomain\Directives\Php\InterfaceDirective;
use T3Docs\GuidesPhpDomain\Directives\Php\MethodDirective;
use T3Docs\GuidesPhpDomain\Directives\Php\NamespaceDirective;
use T3Docs\GuidesPhpDomain\PhpDomain\FullyQualifiedNameService;
use T3Docs\GuidesPhpDomain\PhpDomain\MethodNameService;
use T3Docs\GuidesPhpDomain\PhpDomain\NamespaceRepository;
use T3Docs\GuidesPhpDomain\TextRoles\InterfaceTextRole;

return static function (ContainerConfigurator $container): void {
    $container->services()
        ->defaults()
        ->autowire()
        ->autoconfigure()
        ->instanceof(SubDirective::class)
        ->bind('$startingRule', service(DirectiveContentRule::class))
        ->instanceof(BaseDirective::class)
        ->tag('phpdoc.guides.directive')
        ->set(ConstDirective::class)
        ->set(EnumDirective::class)
        ->set(InterfaceDirective::class)
        ->set(MethodDirective::class)
        ->set(NamespaceDirective::class)
        ->set(FullyQualifiedNameService::class)
        ->set(MethodNameService::class)
        ->set(NamespaceRepository::class)

        ->set(InterfaceTextRole::class)
        ->tag('phpdoc.guides.parser.rst.text_role', ['domain' => 'php'])
    ;
};
