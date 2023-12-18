<?php

declare(strict_types=1);

namespace T3Docs\GuidesPhpDomain\Directives\Php;

use phpDocumentor\Guides\Nodes\CollectionNode;
use phpDocumentor\Guides\Nodes\CompoundNode;
use phpDocumentor\Guides\Nodes\FieldListNode;
use phpDocumentor\Guides\Nodes\FieldLists\FieldListItemNode;
use phpDocumentor\Guides\Nodes\Node;
use phpDocumentor\Guides\ReferenceResolvers\AnchorReducer;
use phpDocumentor\Guides\RestructuredText\Directives\SubDirective;
use phpDocumentor\Guides\RestructuredText\Parser\BlockContext;
use phpDocumentor\Guides\RestructuredText\Parser\Directive;
use phpDocumentor\Guides\RestructuredText\Parser\Productions\InlineMarkupRule;
use phpDocumentor\Guides\RestructuredText\Parser\Productions\Rule;
use phpDocumentor\Guides\RestructuredText\TextRoles\GenericLinkProvider;
use Psr\Log\LoggerInterface;
use T3Docs\GuidesPhpDomain\Nodes\PhpMethodNode;
use T3Docs\GuidesPhpDomain\PhpDomain\MethodNameService;
use T3Docs\GuidesPhpDomain\PhpDomain\ModifierService;

final class MethodDirective extends SubDirective
{
    /**
     * @var string[]
     */
    private array $allowedModifiers = ['abstract', 'final', 'public', 'protected', 'private', 'final', 'static'];
    /** @var list<list<string>>  */
    private array $illegalCombinations = [
        ['private', 'protected'],
        ['private', 'public'],
        ['protected', 'public'],
        ['private', 'abstract'],
        ['final', 'abstract'],
    ];
    public function __construct(
        Rule $startingRule,
        GenericLinkProvider $genericLinkProvider,
        private readonly MethodNameService $methodNameService,
        private readonly AnchorReducer $anchorReducer,
        private readonly LoggerInterface $logger,
        private readonly ModifierService $modifierService,
        private readonly InlineMarkupRule $inlineMarkupRule,
    ) {
        parent::__construct($startingRule);
        $genericLinkProvider->addGenericLink($this->getName(), $this->getName());
    }

    public function getName(): string
    {
        return 'php:method';
    }

    /**
     * @return list<Node>
     */
    public function extractFieldsFromList(CollectionNode $collectionNode, ?CollectionNode &$returnInlineNode, \T3Docs\GuidesPhpDomain\Nodes\MethodNameNode $name, BlockContext $blockContext): array
    {
        $childrenNodes = [];
        foreach ($collectionNode->getChildren() as $childNode) {
            if ($childNode instanceof FieldListNode) {
                $fieldListItems = [];
                foreach ($childNode->getChildren() as $fieldListItem) {
                    if ($fieldListItem instanceof FieldListItemNode) {
                        if (trim(strtolower($fieldListItem->getTerm())) === 'returns') {
                            $returnInlineNode = $this->extractReturnDescriptionFromFieldListItem($returnInlineNode, $fieldListItem, $name->toString(), $blockContext);
                        // the field list item does not get added to the new list, therefore removed
                        } else {
                            $fieldListItems[] = $fieldListItem;
                        }
                    }
                }
                if ($fieldListItems !== []) {
                    $childrenNodes[] = new FieldListNode($fieldListItems);
                }
            } else {
                $childrenNodes[] = $childNode;
            }
        }
        return $childrenNodes;
    }

    private function extractReturnDescriptionFromFieldListItem(?CollectionNode $returnInlineNode, FieldListItemNode $fieldListItem, string $name, BlockContext $blockContext): CollectionNode
    {
        if (!$returnInlineNode instanceof \phpDocumentor\Guides\Nodes\CollectionNode) {
            $returnInlineNode = new CollectionNode($fieldListItem->getChildren());
            $this->logger->info(sprintf("The definition of a return description as field list is deprecated. Define it as an option of the method \n
                                for example:

                                ..  :php:method:: %s
                                    :returns: Your Description
                                ", $name), $blockContext->getLoggerInformation());
        } else {
            $this->logger->warning(sprintf('The return description of method %s has been given multiple times', $name), $blockContext->getLoggerInformation());
        }
        return $returnInlineNode;
    }

    protected function processSub(
        BlockContext $blockContext,
        CollectionNode $collectionNode,
        Directive $directive,
    ): Node|null {
        $name = $this->methodNameService->getMethodName(trim($directive->getData()));
        $id = $this->anchorReducer->reduceAnchor($name->toString());

        $modifiers = $this->modifierService->getModifiersFromDirectiveOptions($directive, $this->allowedModifiers);

        foreach ($this->illegalCombinations as $combination) {
            if ($directive->hasOption($combination[0]) && $directive->hasOption($combination[1])) {
                $this->logger->warning(sprintf('A PHP method cannot be %s and %s at the same time.', $combination[0], $combination[1]), $blockContext->getLoggerInformation());
            }
        }

        $returnInlineNode = null;
        if ($directive->hasOption('returns')) {
            $returnsString = $directive->getOption('returns')->toString();

            $blockContextOfReturnDescription = new BlockContext($blockContext->getDocumentParserContext(), $returnsString);
            $inlineContent = $this->inlineMarkupRule->apply($blockContextOfReturnDescription);
            $returnInlineNode = new CollectionNode([$inlineContent]);
        }

        $childrenNodes = $this->extractFieldsFromList($collectionNode, $returnInlineNode, $name, $blockContext);

        return new PhpMethodNode(
            $id,
            $name,
            $childrenNodes,
            $modifiers,
            $returnInlineNode
        );
    }
}
