<?php

declare(strict_types=1);

namespace T3Docs\GuidesPhpDomain\Nodes;

use phpDocumentor\Guides\Nodes\CollectionNode;
use phpDocumentor\Guides\Nodes\Node;

final class PhpMethodNode extends PhpMemberNode
{
    private const TYPE = 'method';
    /**
     * @param Node[] $value
     * @param PhpModifierNode[] $modifiers
     */
    public function __construct(
        string $id,
        private readonly MethodNameNode $methodName,
        array $value = [],
        private readonly array $modifiers = [],
        private readonly CollectionNode|null $returnsDescription = null,
        readonly bool $noindex = false,
    ) {
        parent::__construct($id, self::TYPE, $methodName->toString(), $value, null, $noindex);
    }

    public function getMethodName(): MethodNameNode
    {
        return $this->methodName;
    }

    /**
     * @return PhpModifierNode[]
     */
    public function getModifiers(): array
    {
        return $this->modifiers;
    }

    public function getReturnsDescription(): ?CollectionNode
    {
        return $this->returnsDescription;
    }
}
