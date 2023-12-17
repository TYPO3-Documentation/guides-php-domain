<?php

declare(strict_types=1);

namespace T3Docs\GuidesPhpDomain\Nodes;

use phpDocumentor\Guides\Nodes\Node;

final class PhpCaseNode extends PhpMemberNode
{
    private const TYPE = 'case';

    /**
     * @param Node[] $value
     */
    public function __construct(
        string                          $id,
        private readonly MemberNameNode $memberName,
        array                           $value = [],
        private readonly string|null    $backedValue = null,
    ) {
        parent::__construct($id, self::TYPE, $memberName->toString(), $value);
    }

    public function getMemberName(): MemberNameNode
    {
        return $this->memberName;
    }

    public function getBackedValue(): ?string
    {
        return $this->backedValue;
    }
}
