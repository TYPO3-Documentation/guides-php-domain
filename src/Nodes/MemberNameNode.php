<?php

namespace T3Docs\GuidesPhpDomain\Nodes;

use phpDocumentor\Guides\Nodes\AbstractNode;

/**
 * Stores data on PHP namespaces
 * @extends AbstractNode<string>
 */
class MemberNameNode extends AbstractNode
{
    public function __construct(
        private readonly string $name,
    ) {
        $this->value = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function toString(): string
    {
        return $this->name;
    }
}
