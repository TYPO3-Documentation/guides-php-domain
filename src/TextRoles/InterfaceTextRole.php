<?php

declare(strict_types=1);

namespace T3Docs\GuidesPhpDomain\TextRoles;

final class InterfaceTextRole extends PhpComponentTextRole
{
    private const TYPE = 'interface';

    public function getName(): string
    {
        return self::TYPE;
    }
}
