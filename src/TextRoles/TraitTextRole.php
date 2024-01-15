<?php

declare(strict_types=1);

namespace T3Docs\GuidesPhpDomain\TextRoles;

final class TraitTextRole extends PhpComponentTextRole
{
    private const TYPE = 'trait';

    public function getName(): string
    {
        return self::TYPE;
    }
}
