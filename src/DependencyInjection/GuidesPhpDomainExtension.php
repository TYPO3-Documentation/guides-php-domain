<?php

declare(strict_types=1);

namespace T3Docs\GuidesPhpDomain\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use T3Docs\GuidesPhpDomain\Nodes\MemberNameNode;
use T3Docs\GuidesPhpDomain\Nodes\PhpCaseNode;
use T3Docs\GuidesPhpDomain\Nodes\PhpComponentNode;
use T3Docs\GuidesPhpDomain\Nodes\PhpConstNode;
use T3Docs\GuidesPhpDomain\Nodes\PhpGlobalNode;
use T3Docs\GuidesPhpDomain\Nodes\PhpMethodNode;
use T3Docs\GuidesPhpDomain\Nodes\PhpModifierNode;
use T3Docs\GuidesPhpDomain\Nodes\PhpNamespaceNode;

use T3Docs\GuidesPhpDomain\Nodes\PhpPropertyNode;

use function dirname;
use function phpDocumentor\Guides\DependencyInjection\template;

final class GuidesPhpDomainExtension extends Extension implements PrependExtensionInterface
{
    /** @param mixed[] $configs */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new PhpFileLoader(
            $container,
            new FileLocator(dirname(__DIR__, 2) . '/resources/config'),
        );
        $loader->load('php-domain.php');
    }

    public function prepend(ContainerBuilder $container): void
    {
        $container->prependExtensionConfig(
            'guides',
            [
                'base_template_paths' => [dirname(__DIR__, 2) . '/resources/template/html'],
                'templates' => [
                    template(PhpCaseNode::class, 'body/directive/php/case.html.twig'),
                    template(PhpComponentNode::class, 'body/directive/php/component.html.twig'),
                    template(PhpConstNode::class, 'body/directive/php/const.html.twig'),
                    template(PhpGlobalNode::class, 'body/directive/php/global.html.twig'),
                    template(PhpNamespaceNode::class, 'body/directive/php/namespace.html.twig'),
                    template(PhpMethodNode::class, 'body/directive/php/method.html.twig'),
                    template(PhpPropertyNode::class, 'body/directive/php/property.html.twig'),
                    template(PhpModifierNode::class, 'body/directive/php/modifier.html.twig'),
                    template(MemberNameNode::class, 'body/directive/php/memberName.html.twig'),
                ],
            ],
        );
    }
}
