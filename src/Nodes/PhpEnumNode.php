<?php

declare(strict_types=1);

namespace T3Docs\GuidesPhpDomain\Nodes;

use phpDocumentor\Guides\Nodes\Node;

final class PhpEnumNode extends PhpComponentNode
{
    private const TYPE = 'enum';

    /**
     * @param list<PhpMemberNode> $members
     * @param list<PhpModifierNode> $modifiers
     * @param list<Node> $value
     */
    public function __construct(
        string $id,
        FullyQualifiedNameNode $name,
        array $value = [],
        PhpNamespaceNode|null $namespace = null,
        array $members = [],
        array $modifiers = [],
    ) {
        parent::__construct($id, self::TYPE, $name, $value, $namespace, $members, $modifiers);
    }
}
