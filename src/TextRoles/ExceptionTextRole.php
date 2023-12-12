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

final class ExceptionTextRole extends PhpComponentTextRole
{
    private const TYPE = 'exception';

    public function getAliases(): array
    {
        return ['exc'];
    }

    public function getName(): string
    {
        return self::TYPE;
    }

    public function processNode(
        DocumentParserContext $documentParserContext,
        string $role,
        string $content,
        string $rawContent,
    ): AbstractLinkInlineNode {
        if ($role !== 'php:exception') {
            $this->logger->warning(sprintf('Text role :%s: is deprecated. Use :php:exception: instead. ', $role), $documentParserContext->getLoggerInformation());
        }
        return parent::processNode($documentParserContext, $role, $content, $rawContent);
    }
}
