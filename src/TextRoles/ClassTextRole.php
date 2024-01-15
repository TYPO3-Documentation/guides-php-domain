<?php

declare(strict_types=1);

namespace T3Docs\GuidesPhpDomain\TextRoles;

final class ClassTextRole extends PhpComponentTextRole
{
    private const TYPE = 'class';

    public function getName(): string
    {
        return self::TYPE;
    }
}
