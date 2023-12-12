<?php

declare(strict_types=1);

namespace T3Docs\GuidesPhpDomain\TextRoles;

use Doctrine\Common\Lexer\Token;
use phpDocumentor\Guides\Nodes\Inline\AbstractLinkInlineNode;
use phpDocumentor\Guides\Nodes\Inline\ReferenceNode;
use phpDocumentor\Guides\ReferenceResolvers\AnchorReducer;
use phpDocumentor\Guides\RestructuredText\Parser\DocumentParserContext;
use phpDocumentor\Guides\RestructuredText\Parser\InlineLexer;
use phpDocumentor\Guides\RestructuredText\TextRoles\TextRole;
use Psr\Log\LoggerInterface;

final class ClassTextRole extends PhpComponentTextRole
{
    private const TYPE = 'class';

    public function getName(): string
    {
        return self::TYPE;
    }
}
