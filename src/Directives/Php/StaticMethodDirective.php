<?php

declare(strict_types=1);

namespace T3Docs\GuidesPhpDomain\Directives\Php;

use phpDocumentor\Guides\Nodes\CollectionNode;
use phpDocumentor\Guides\Nodes\Node;
use phpDocumentor\Guides\ReferenceResolvers\AnchorNormalizer;
use phpDocumentor\Guides\RestructuredText\Directives\SubDirective;
use phpDocumentor\Guides\RestructuredText\Parser\BlockContext;
use phpDocumentor\Guides\RestructuredText\Parser\Directive;
use phpDocumentor\Guides\RestructuredText\Parser\Productions\Rule;
use phpDocumentor\Guides\RestructuredText\TextRoles\GenericLinkProvider;
use Psr\Log\LoggerInterface;
use T3Docs\GuidesPhpDomain\Nodes\PhpMethodNode;
use T3Docs\GuidesPhpDomain\Nodes\PhpModifierNode;
use T3Docs\GuidesPhpDomain\PhpDomain\MethodNameService;

final class StaticMethodDirective extends SubDirective
{
    public function __construct(
        Rule                               $startingRule,
        GenericLinkProvider                $genericLinkProvider,
        private readonly MethodNameService $methodNameService,
        private readonly AnchorNormalizer  $anchorNormalizer,
        private readonly LoggerInterface   $logger,
    ) {
        parent::__construct($startingRule);
        $genericLinkProvider->addGenericLink($this->getName(), $this->getName());
    }

    public function getName(): string
    {
        return 'php:staticmethod';
    }

    protected function processSub(
        BlockContext $blockContext,
        CollectionNode $collectionNode,
        Directive $directive,
    ): Node|null {
        $this->logger->info(
            'Directive `.. php:staticmethod::` is deprecated use directive `.. php:method::` with option `:static:` instead. ',
            $blockContext->getLoggerInformation()
        );
        $name = $this->methodNameService->getMethodName(trim($directive->getData()));
        $id = $this->anchorNormalizer->reduceAnchor($name->toString());

        return new PhpMethodNode(
            $id,
            $name,
            $collectionNode->getChildren(),
            [new PhpModifierNode(PhpModifierNode::STATIC)]
        );
    }
}
