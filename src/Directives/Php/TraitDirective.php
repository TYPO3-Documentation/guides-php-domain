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
use T3Docs\GuidesPhpDomain\Nodes\PhpTraitNode;
use T3Docs\GuidesPhpDomain\PhpDomain\FullyQualifiedNameService;

final class TraitDirective extends SubDirective
{
    use ComponentTrait;
    public function __construct(
        Rule                                       $startingRule,
        GenericLinkProvider                        $genericLinkProvider,
        private readonly FullyQualifiedNameService $fullyQualifiedNameService,
        private readonly AnchorNormalizer          $anchorNormalizer,
    ) {
        parent::__construct($startingRule);
        $genericLinkProvider->addGenericLink($this->getName(), $this->getName());
    }

    public function getName(): string
    {
        return 'php:trait';
    }

    protected function processSub(
        BlockContext $blockContext,
        CollectionNode $collectionNode,
        Directive $directive,
    ): Node|null {
        $name = trim($directive->getData());
        $fqn = $this->fullyQualifiedNameService->getFullyQualifiedName($name, true);
        $id = $this->anchorNormalizer->reduceAnchor($fqn->toString());

        $isnoindex = $directive->hasOption('noindex');
        $node = new PhpTraitNode(
            $id,
            $fqn,
            $collectionNode->getChildren(),
            null,
            [],
            $isnoindex,
        );

        $this->setParentsForMembers($collectionNode, $node);
        return $node;
    }
}
