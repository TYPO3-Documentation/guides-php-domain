<?php

declare(strict_types=1);

namespace T3Docs\GuidesPhpDomain\Nodes;

use phpDocumentor\Guides\Nodes\CompoundNode;
use phpDocumentor\Guides\Nodes\LinkTargetNode;
use phpDocumentor\Guides\Nodes\Node;
use phpDocumentor\Guides\Nodes\OptionalLinkTargetsNode;

/**
 * Stores data on global PHP variables
 *
 * @extends CompoundNode<Node>
 */
final class PhpGlobalNode extends CompoundNode implements LinkTargetNode, OptionalLinkTargetsNode
{
    private const TYPE = 'global';
    /**
     * @param list<Node> $value
     */
    public function __construct(
        private readonly string $id,
        private readonly string $name,
        array $value = [],
        readonly bool $noindex = false,
    ) {
        parent::__construct($value);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getLinkType(): string
    {
        return 'php:' . self::TYPE;
    }

    public function getLinkText(): string
    {
        return $this->getName();
    }

    public function isNoindex(): bool
    {
        return $this->noindex;
    }
}
