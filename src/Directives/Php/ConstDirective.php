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
use T3Docs\GuidesPhpDomain\Nodes\MemberNameNode;
use T3Docs\GuidesPhpDomain\Nodes\PhpConstNode;
use T3Docs\GuidesPhpDomain\PhpDomain\ModifierService;

final class ConstDirective extends SubDirective
{
    /**
     * @var string[]
     */
    private array $allowedModifiers = ['public', 'protected', 'private'];

    /** @var list<list<string>>  */
    private array $illegalCombinations = [
        ['private', 'protected'],
        ['private', 'public'],
        ['protected', 'public'],
    ];
    public function __construct(
        Rule                              $startingRule,
        GenericLinkProvider               $genericLinkProvider,
        private readonly AnchorNormalizer $anchorNormalizer,
        private readonly LoggerInterface  $logger,
        private readonly ModifierService  $modifierService,
    ) {
        parent::__construct($startingRule);
        $genericLinkProvider->addGenericLink($this->getName(), $this->getName());
    }

    public function getName(): string
    {
        return 'php:const';
    }

    protected function processSub(
        BlockContext $blockContext,
        CollectionNode $collectionNode,
        Directive $directive,
    ): Node|null {
        $name = new MemberNameNode(trim($directive->getData()));
        $id = $this->anchorNormalizer->reduceAnchor($name->toString());
        $modifiers = $this->modifierService->getModifiersFromDirectiveOptions($directive, $this->allowedModifiers);
        $isnoindex = $directive->hasOption('noindex');

        foreach ($this->illegalCombinations as $combination) {
            if ($directive->hasOption($combination[0]) && $directive->hasOption($combination[1])) {
                $this->logger->warning(sprintf('A PHP constant cannot be %s and %s at the same time.', $combination[0], $combination[1]), $blockContext->getLoggerInformation());
            }
        }

        $type = null;
        if ($directive->hasOption('type')) {
            $type = $directive->getOption('type')->toString();
        }

        return new PhpConstNode(
            $id,
            $name,
            $collectionNode->getChildren(),
            $modifiers,
            $type,
            $isnoindex,
        );
    }
}
