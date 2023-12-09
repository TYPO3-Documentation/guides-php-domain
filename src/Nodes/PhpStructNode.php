<?php

declare(strict_types=1);

namespace T3Docs\GuidesPhpDomain\Nodes;

use phpDocumentor\Guides\Nodes\Node;

final class PhpStructNode extends PhpComponentNode
{
    private const TYPE = 'struct';

    /**
     * @param list<Node> $value
     * @param list<PhpMemberNode> $members
     */
    public function __construct(
        string $id,
        FullyQualifiedNameNode $name,
        array $value = [],
        PhpNamespaceNode|null $namespace = null,
        array $members = [],
    ) {
        parent::__construct($id, self::TYPE, $name, $value, $namespace, $members);
    }
}
