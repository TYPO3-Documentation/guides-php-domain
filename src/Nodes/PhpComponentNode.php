<?php

declare(strict_types=1);

namespace T3Docs\GuidesPhpDomain\Nodes;

use phpDocumentor\Guides\Nodes\CompoundNode;
use phpDocumentor\Guides\Nodes\LinkTargetNode;
use phpDocumentor\Guides\Nodes\Node;
use phpDocumentor\Guides\Nodes\OptionalLinkTargetsNode;

/**
 * Stores data on PHP classes, interfaces, traits and enums
 *
 * @extends CompoundNode<Node>
 */
abstract class PhpComponentNode extends CompoundNode implements LinkTargetNode, OptionalLinkTargetsNode
{
    /**
     * @param list<PhpMemberNode> $members
     * @param list<PhpModifierNode> $modifiers
     * @param list<Node> $value
     */
    public function __construct(
        private readonly string $id,
        private readonly string $type,
        private readonly FullyQualifiedNameNode $name,
        array $value = [],
        private PhpNamespaceNode|null $namespace = null,
        private array $members = [],
        private readonly array $modifiers = [],
        private readonly bool $noindex = false,
    ) {
        parent::__construct($value);
    }

    public function getLinkType(): string
    {
        return 'php:' . $this->type;
    }

    public function getId(): string
    {
        return $this->id;
    }
    public function getLinkText(): string
    {
        return $this->toString();
    }

    public function getNamespace(): ?PhpNamespaceNode
    {
        return $this->namespace;
    }

    public function setNamespace(?PhpNamespaceNode $namespace): void
    {
        $this->namespace = $namespace;
    }

    /**
     * @return list<PhpMemberNode>
     */
    public function getMembers(): array
    {
        return $this->members;
    }

    /**
     * @param list<PhpMemberNode> $members
     */
    public function setMembers(array $members): void
    {
        $this->members = $members;
    }

    public function getName(): FullyQualifiedNameNode
    {
        return $this->name;
    }

    public function toString(): string
    {
        return $this->name->toString();
    }

    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return list<PhpModifierNode>
     */
    public function getModifiers(): array
    {
        return $this->modifiers;
    }

    public function isNoindex(): bool
    {
        return $this->noindex;
    }
}
