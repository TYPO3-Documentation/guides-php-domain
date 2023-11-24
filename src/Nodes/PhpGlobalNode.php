<?php

declare(strict_types=1);

namespace T3Docs\GuidesPhpDomain\Nodes;

use phpDocumentor\Guides\Nodes\CompoundNode;
use phpDocumentor\Guides\Nodes\Node;

/**
 * Stores data on global PHP variables
 *
 * @extends CompoundNode<Node>
 */
final class PhpGlobalNode extends CompoundNode
{
    /**
     * @param list<Node> $value
     */
    public function __construct(
        private readonly string $id,
        private readonly string $name,
        array $value = [],
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
}
