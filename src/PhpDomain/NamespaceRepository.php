<?php

declare(strict_types=1);

namespace T3Docs\GuidesPhpDomain\PhpDomain;

use T3Docs\GuidesPhpDomain\Nodes\PhpNamespaceNode;

class NamespaceRepository
{
    private PhpNamespaceNode|null $currentNamespace = null;

    public function getCurrentNamespace(): ?PhpNamespaceNode
    {
        return $this->currentNamespace;
    }

    public function setCurrentNamespace(?PhpNamespaceNode $currentNamespace): void
    {
        $this->currentNamespace = $currentNamespace;
    }
}
