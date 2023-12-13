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
use T3Docs\GuidesPhpDomain\Nodes\MemberNameNode;
use T3Docs\GuidesPhpDomain\Nodes\PhpConstNode;
use T3Docs\GuidesPhpDomain\Nodes\PhpPropertyNode;
use T3Docs\GuidesPhpDomain\PhpDomain\ModifierService;

final class PropertyDirective extends SubDirective
{
    /**
     * @var string[]
     */
    private array $allowedModifiers = ['public', 'protected', 'private', 'static', 'readonly'];

    /** @var list<list<string>>  */
    private array $illegalCombinations = [
        ['private', 'protected'],
        ['private', 'public'],
        ['protected', 'public'],
        ['static', 'readonly'],
    ];
    public function __construct(
        Rule $startingRule,
        GenericLinkProvider $genericLinkProvider,
        private readonly AnchorReducer $anchorReducer,
        private readonly LoggerInterface $logger,
        private readonly ModifierService $modifierService,
    ) {
        parent::__construct($startingRule);
        $genericLinkProvider->addGenericLink($this->getName(), $this->getName());
    }

    public function getName(): string
    {
        return 'php:property';
    }
    /**
     * @return string[]
     */
    public function getAliases(): array
    {
        return [
            'php:attr',
        ];
    }

    protected function processSub(
        BlockContext $blockContext,
        CollectionNode $collectionNode,
        Directive $directive,
    ): Node|null {
        $name = new MemberNameNode(trim($directive->getData()));
        $id = $this->anchorReducer->reduceAnchor($name->toString());
        $modifiers = $this->modifierService->getModifiersFromDirectiveOptions($directive, $this->allowedModifiers);

        if ($directive->getName() !== 'php:property') {
            $this->logger->warning(sprintf('Using directive `%s` is deprecated, use directive `php:property` instead.', $directive->getName()), $blockContext->getLoggerInformation());
        }

        foreach ($this->illegalCombinations as $combination) {
            if ($directive->hasOption($combination[0]) && $directive->hasOption($combination[1])) {
                $this->logger->warning(sprintf('A PHP property cannot be %s and %s at the same time.', $combination[0], $combination[1]), $blockContext->getLoggerInformation());
            }
        }
        $type = null;
        if ($directive->hasOption('type')) {
            $type = $directive->getOption('type')->toString();
        }

        return new PhpPropertyNode(
            $id,
            $name,
            $collectionNode->getChildren(),
            $type,
            $modifiers,
        );
    }
}
