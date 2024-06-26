<?php

declare(strict_types=1);

namespace T3Docs\GuidesPhpDomain\Nodes;

use phpDocumentor\Guides\Nodes\Node;

final class PhpConstNode extends PhpMemberNode
{
    private const TYPE = 'const';

    /**
     * @param Node[] $value
     * @param PhpModifierNode[] $modifiers
     */
    public function __construct(
        string                          $id,
        private readonly MemberNameNode $memberName,
        array                           $value = [],
        private readonly array $modifiers = [],
        private readonly string|null    $phpType = null,
        readonly bool $noindex = false,
    ) {
        parent::__construct($id, self::TYPE, $memberName->toString(), $value, null, $noindex);
    }

    public function getMemberName(): MemberNameNode
    {
        return $this->memberName;
    }

    /**
     * @return PhpModifierNode[]
     */
    public function getModifiers(): array
    {
        return $this->modifiers;
    }

    public function getPhpType(): ?string
    {
        return $this->phpType;
    }
}
