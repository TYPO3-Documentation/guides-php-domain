<?php

declare(strict_types=1);

namespace T3Docs\GuidesPhpDomain\Directives\Php;

use phpDocumentor\Guides\Nodes\CollectionNode;
use phpDocumentor\Guides\Nodes\Node;
use phpDocumentor\Guides\ReferenceResolvers\AnchorReducer;
use phpDocumentor\Guides\RestructuredText\Directives\SubDirective;
use phpDocumentor\Guides\RestructuredText\Parser\BlockContext;
use phpDocumentor\Guides\RestructuredText\Parser\Directive;
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
    ) {
        parent::__construct($startingRule);
        $genericLinkProvider->addGenericLink($this->getName(), $this->getName());
    }

    public function getName(): string
    {
        return 'php:method';
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
                $this->logger->warning(sprintf('A PHP method cannot be %s and %s at the same time.', $combination[0], $combination[1]), $blockContext->getDocumentParserContext()->getLoggerInformation());
            }
        }

        return new PhpMethodNode(
            $id,
            $name,
            $collectionNode->getChildren(),
            $modifiers
        );
    }
}
