<?php

declare(strict_types=1);

namespace T3Docs\GuidesPhpDomain\PhpDomain;

use phpDocumentor\Guides\RestructuredText\Parser\Directive;
use T3Docs\GuidesPhpDomain\Nodes\PhpModifierNode;

final class ModifierService
{
    /**
     * @param list<string> $allowedModifiers
     * @return list<PhpModifierNode>
     */
    public function getModifiersFromDirectiveOptions(Directive $directive, array $allowedModifiers): array
    {
        $modifiers = [];

        foreach ($allowedModifiers as $allowedModifier) {
            if ($directive->hasOption($allowedModifier)) {
                $modifiers[] = new PhpModifierNode($allowedModifier);
            }
        }
        return $modifiers;
    }

}
