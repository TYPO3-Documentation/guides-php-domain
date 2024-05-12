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
use T3Docs\GuidesPhpDomain\Nodes\MemberNameNode;
use T3Docs\GuidesPhpDomain\Nodes\PhpCaseNode;

final class CaseDirective extends SubDirective
{
    public function __construct(
        Rule                              $startingRule,
        GenericLinkProvider               $genericLinkProvider,
        private readonly AnchorNormalizer $anchorNormalizer,
    ) {
        parent::__construct($startingRule);
        $genericLinkProvider->addGenericLink($this->getName(), $this->getName());
    }

    public function getName(): string
    {
        return 'php:case';
    }

    protected function processSub(
        BlockContext $blockContext,
        CollectionNode $collectionNode,
        Directive $directive,
    ): Node|null {
        $name = new MemberNameNode(trim($directive->getData()));
        $id = $this->anchorNormalizer->reduceAnchor($name->toString());
        $isnoindex = $directive->hasOption('noindex');

        $value = null;
        if ($directive->hasOption('value')) {
            $value = $directive->getOption('value')->toString();
        }

        return new PhpCaseNode(
            $id,
            $name,
            $collectionNode->getChildren(),
            $value,
            $isnoindex,
        );
    }
}
