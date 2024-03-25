<?php

namespace T3Docs\GuidesPhpDomain\Directives\Php;

use phpDocumentor\Guides\Nodes\CollectionNode;
use T3Docs\GuidesPhpDomain\Nodes\PhpComponentNode;
use T3Docs\GuidesPhpDomain\Nodes\PhpMemberNode;

trait ComponentTrait
{
    private function setParentsForMembers(CollectionNode $collectionNode, PhpComponentNode $node): void
    {
        foreach ($collectionNode->getChildren() as $child) {
            if (!$child instanceof PhpMemberNode) {
                continue;
            }
            $child->setParentComponent($node);
        }
    }
}
