<?php

declare(strict_types=1);

namespace T3Docs\GuidesPhpDomain\TextRoles;

final class EnumTextRole extends PhpComponentTextRole
{
    private const TYPE = 'enum';

    public function getName(): string
    {
        return self::TYPE;
    }
}
