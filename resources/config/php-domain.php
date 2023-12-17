<?php

declare(strict_types=1);

use phpDocumentor\Guides\RestructuredText\Directives\BaseDirective;
use phpDocumentor\Guides\RestructuredText\Directives\SubDirective;
use phpDocumentor\Guides\RestructuredText\Parser\Productions\DirectiveContentRule;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use T3Docs\GuidesPhpDomain\Compiler\NodeTransformers\MemberNodeTransformer;
use T3Docs\GuidesPhpDomain\Directives\Php\CaseDirective;
use T3Docs\GuidesPhpDomain\Directives\Php\ClassDirective;
use T3Docs\GuidesPhpDomain\Directives\Php\ConstDirective;
use T3Docs\GuidesPhpDomain\Directives\Php\ExceptionDirective;
use T3Docs\GuidesPhpDomain\Directives\Php\PropertyDirective;
use T3Docs\GuidesPhpDomain\Directives\Php\StaticMethodDirective;
use T3Docs\GuidesPhpDomain\Directives\Php\GlobalDirective;
use T3Docs\GuidesPhpDomain\Directives\Php\TraitDirective;
use T3Docs\GuidesPhpDomain\PhpDomain\ModifierService;
use T3Docs\GuidesPhpDomain\TextRoles\CaseTextRole;
use T3Docs\GuidesPhpDomain\TextRoles\ClassTextRole;
use T3Docs\GuidesPhpDomain\TextRoles\ConstTextRole;
use T3Docs\GuidesPhpDomain\TextRoles\EnumTextRole;
use T3Docs\GuidesPhpDomain\TextRoles\ExceptionTextRole;
use T3Docs\GuidesPhpDomain\TextRoles\MethodTextRole;
use T3Docs\GuidesPhpDomain\TextRoles\TraitTextRole;
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
        ->set(CaseDirective::class)
        ->set(ClassDirective::class)
        ->set(ConstDirective::class)
        ->set(EnumDirective::class)
        ->set(ExceptionDirective::class)
        ->set(GlobalDirective::class)
        ->set(InterfaceDirective::class)
        ->set(MethodDirective::class)
        ->set(NamespaceDirective::class)
        ->set(PropertyDirective::class)
        ->set(StaticMethodDirective::class)
        ->set(TraitDirective::class)
        ->set(FullyQualifiedNameService::class)
        ->set(MethodNameService::class)
        ->set(ModifierService::class)
        ->set(NamespaceRepository::class)

        ->set(MemberNodeTransformer::class)
        ->tag('phpdoc.guides.compiler.nodeTransformers')

        ->set(ClassTextRole::class)
        ->tag('phpdoc.guides.parser.rst.text_role', ['domain' => 'php'])
        ->set(CaseTextRole::class)
        ->tag('phpdoc.guides.parser.rst.text_role', ['domain' => 'php'])
        ->set(ConstTextRole::class)
        ->tag('phpdoc.guides.parser.rst.text_role', ['domain' => 'php'])
        ->set(EnumTextRole::class)
        ->tag('phpdoc.guides.parser.rst.text_role', ['domain' => 'php'])
        ->set(ExceptionTextRole::class)
        ->tag('phpdoc.guides.parser.rst.text_role', ['domain' => 'php'])
        ->set(InterfaceTextRole::class)
        ->tag('phpdoc.guides.parser.rst.text_role', ['domain' => 'php'])
        ->set(MethodTextRole::class)
        ->tag('phpdoc.guides.parser.rst.text_role', ['domain' => 'php'])
        ->set(TraitTextRole::class)
        ->tag('phpdoc.guides.parser.rst.text_role', ['domain' => 'php'])
    ;
};
