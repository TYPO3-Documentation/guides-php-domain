<?php

declare(strict_types=1);

namespace T3Docs\GuidesPhpDomain\Nodes;

use phpDocumentor\Guides\Nodes\CompoundNode;
use phpDocumentor\Guides\Nodes\LinkTargetNode;
use phpDocumentor\Guides\Nodes\Node;

/**
 * Stores data on constants, methods and properties
 *
 * @extends CompoundNode<Node>
 */
abstract class PhpMemberNode extends CompoundNode implements LinkTargetNode
{
    public function __construct(
        private string $id,
        private readonly string $type,
        private readonly string $name,
        array $value = [],
        private ?PhpComponentNode $parentComponent = null,
    ) {
        parent::__construct($value);
    }

    public function withId(string $id): PhpMemberNode
    {
        $clone = clone($this);
        $clone->id = $id;
        return $clone;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLinkType(): string
    {
        return 'php:' . $this->type;
    }
    public function getLinkText(): string
    {
        return $this->getFullyQualifiedName();
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getParentComponent(): ?PhpComponentNode
    {
        return $this->parentComponent;
    }

    public function setParentComponent(?PhpComponentNode $parentComponent): void
    {
        $this->parentComponent = $parentComponent;
    }

    public function getFullyQualifiedName(): string
    {
        if ($this->parentComponent == null) {
            return $this->getName();
        }
        return $this->parentComponent->getName()->toString() . '::' . $this->getName();
    }
}
