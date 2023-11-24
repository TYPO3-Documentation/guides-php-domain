<?php

declare(strict_types=1);

namespace T3Docs\GuidesPhpDomain\Nodes;

use phpDocumentor\Guides\Nodes\Node;

final class PhpConstNode extends PhpMemberNode
{
    private const TYPE = 'const';

    /**
     * @param Node[] $value
     */
    public function __construct(
        string                          $id,
        private readonly MemberNameNode $memberName,
        array                           $value = [],
    ) {
        parent::__construct($id, self::TYPE, $memberName->toString(), $value);
    }

    public function getMemberName(): MemberNameNode
    {
        return $this->memberName;
    }
}
