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
use T3Docs\GuidesPhpDomain\Nodes\PhpClassNode;
use T3Docs\GuidesPhpDomain\PhpDomain\FullyQualifiedNameService;
use T3Docs\GuidesPhpDomain\PhpDomain\ModifierService;

final class ClassDirective extends SubDirective
{
    /**
     * @var string[]
     */
    private array $allowedModifiers = ['abstract', 'final'];
    public function __construct(
        Rule                                       $startingRule,
        GenericLinkProvider                        $genericLinkProvider,
        private readonly FullyQualifiedNameService $fullyQualifiedNameService,
        private readonly AnchorNormalizer          $anchorNormalizer,
        private readonly LoggerInterface           $logger,
        private readonly ModifierService           $modifierService,
    ) {
        parent::__construct($startingRule);
        $genericLinkProvider->addGenericLink($this->getName(), $this->getName());
    }

    public function getName(): string
    {
        return 'php:class';
    }

    protected function processSub(
        BlockContext $blockContext,
        CollectionNode $collectionNode,
        Directive $directive,
    ): Node|null {
        $name = trim($directive->getData());
        $fqn = $this->fullyQualifiedNameService->getFullyQualifiedName($name, true);
        $id = $this->anchorNormalizer->reduceAnchor($fqn->toString());
        $modifiers = $this->modifierService->getModifiersFromDirectiveOptions($directive, $this->allowedModifiers);

        if ($directive->hasOption('abstract') && $directive->hasOption('final')) {
            $this->logger->warning('A PHP class cannot be abstract and final at the same time.', $blockContext->getLoggerInformation());
        }

        return new PhpClassNode(
            $id,
            $fqn,
            $collectionNode->getChildren(),
            null,
            [],
            $modifiers,
        );
    }
}
