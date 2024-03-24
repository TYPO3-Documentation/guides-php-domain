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
use T3Docs\GuidesPhpDomain\Nodes\PhpEnumNode;
use T3Docs\GuidesPhpDomain\PhpDomain\FullyQualifiedNameService;

final class EnumDirective extends SubDirective
{
    use ComponentTrait;
    public function __construct(
        Rule                                       $startingRule,
        GenericLinkProvider                        $genericLinkProvider,
        private readonly FullyQualifiedNameService $fullyQualifiedNameService,
        private readonly AnchorNormalizer          $anchorNormalizer,
        private readonly LoggerInterface           $logger,
    ) {
        parent::__construct($startingRule);
        $genericLinkProvider->addGenericLink($this->getName(), $this->getName());
    }

    public function getName(): string
    {
        return 'php:enum';
    }

    protected function processSub(
        BlockContext $blockContext,
        CollectionNode $collectionNode,
        Directive $directive,
    ): Node|null {
        $name = trim($directive->getData());
        $type = null;

        if (str_contains($name, ':')) {
            [$name, $type] = explode(':', $name, 2);
            $type = trim($type);
            $this->logger->info('Passing the type of a backed enum directly with the name is deprecated. Use option :type: instead.', $blockContext->getLoggerInformation());
        }

        $fqn = $this->fullyQualifiedNameService->getFullyQualifiedName(trim($name), true);

        $id = $this->anchorNormalizer->reduceAnchor($fqn->toString());

        if ($directive->hasOption('type')) {
            if ($type != null) {
                $this->logger->warning('The type of the backed enum was set twice. The type from the option will be prefered.', $blockContext->getLoggerInformation());
            }
            $type = $directive->getOption('type')->toString();
        }

        $node = new PhpEnumNode(
            $id,
            $fqn,
            $collectionNode->getChildren(),
            null,
            [],
            [],
            $type,
        );

        $this->setParentsForMembers($collectionNode, $node);
        return $node;
    }
}
