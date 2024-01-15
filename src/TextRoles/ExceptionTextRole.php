<?php

declare(strict_types=1);

namespace T3Docs\GuidesPhpDomain\TextRoles;

use phpDocumentor\Guides\Nodes\Inline\AbstractLinkInlineNode;
use phpDocumentor\Guides\RestructuredText\Parser\DocumentParserContext;

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
            $this->logger->info(sprintf('Text role :%s: is deprecated. Use :php:exception: instead. ', $role), $documentParserContext->getLoggerInformation());
        }
        return parent::processNode($documentParserContext, $role, $content, $rawContent);
    }
}
