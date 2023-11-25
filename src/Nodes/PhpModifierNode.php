<?php

declare(strict_types=1);

namespace T3Docs\GuidesPhpDomain\Nodes;

use phpDocumentor\Guides\Nodes\AbstractNode;
use phpDocumentor\Guides\Nodes\Node;

/**
 * Stores data on PHP modifiers for classes, methods, attributes, etc
 * @extends AbstractNode<string>
 */
final class PhpModifierNode extends AbstractNode
{
    public const STATIC = 'static';
    public function __construct(
        private readonly string $type,
    ) {
        $this->value = $type;
    }

    public function getType(): string
    {
        return $this->type;
    }
}
