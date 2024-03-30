<?php

declare(strict_types=1);

namespace T3Docs\GuidesPhpDomain\TextRoles;

use phpDocumentor\Guides\Nodes\Inline\ReferenceNode;
use phpDocumentor\Guides\ReferenceResolvers\AnchorNormalizer;
use phpDocumentor\Guides\RestructuredText\Parser\DocumentParserContext;
use Psr\Log\LoggerInterface;

final class GlobalTextRole extends PhpComponentTextRole
{
    private const TYPE = 'global';

    public function __construct(
        LoggerInterface                   $logger,
        private readonly AnchorNormalizer $anchorNormalizer,
    ) {
        parent::__construct($logger, $anchorNormalizer);
    }

    protected function createNode(DocumentParserContext $documentParserContext, string $referenceTarget, string|null $referenceName, string $role): ReferenceNode
    {
        if (preg_match(self::INTERLINK_NAME_REGEX, $referenceTarget, $matches)) {
            return $this->createNodeWithInterlink($matches[2], $matches[1], $referenceName);
        }
        return $this->createNodeWithInterlink($referenceTarget, '', $referenceName);
    }

    private function createNodeWithInterlink(string $referenceTarget, string $interlinkDomain, string|null $referenceName): ReferenceNode
    {
        $id = $this->anchorNormalizer->reduceAnchor($referenceTarget);

        return new ReferenceNode($id, $referenceName ?? $referenceTarget, $interlinkDomain, 'php:' . $this->getName());
    }

    public function getName(): string
    {
        return self::TYPE;
    }

    /**
     * @return list<string>
     */
    public function getAliases(): array
    {
        return [];
    }
}
