<?php

declare(strict_types=1);

namespace T3Docs\GuidesPhpDomain\Nodes;

use phpDocumentor\Guides\Nodes\Node;

final class PhpPropertyNode extends PhpMemberNode
{
    private const TYPE = 'property';

    /**
     * @param Node[] $value
     * @param PhpModifierNode[] $modifiers
     */
    public function __construct(
        string                          $id,
        private readonly MemberNameNode $memberName,
        array                           $value = [],
        private readonly string|null    $phpType = null,
        private readonly array          $modifiers = [],
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

    public function getPhpType(): string|null
    {
        return $this->phpType;
    }

}
