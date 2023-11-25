<?php

declare(strict_types=1);

namespace T3Docs\GuidesPhpDomain\Nodes;

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
    ) {
        parent::__construct($id, self::TYPE, $methodName->toString(), $value);
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
}
