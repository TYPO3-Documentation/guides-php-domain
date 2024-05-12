<?php

declare(strict_types=1);

namespace T3Docs\GuidesPhpDomain\Nodes;

use phpDocumentor\Guides\Nodes\Node;

final class PhpInterfaceNode extends PhpComponentNode
{
    private const TYPE = 'interface';
    /**
     * @param list<PhpMemberNode> $members
     * @param list<Node> $value
     */
    public function __construct(
        string $id,
        FullyQualifiedNameNode $name,
        array $value = [],
        PhpNamespaceNode|null $namespace = null,
        array $members = [],
        readonly bool $noindex = false,
    ) {
        parent::__construct($id, self::TYPE, $name, $value, $namespace, $members, [], $noindex);
    }
}
