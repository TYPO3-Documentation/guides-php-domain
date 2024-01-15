<?php

declare(strict_types=1);

namespace T3Docs\GuidesPhpDomain\TextRoles;

use phpDocumentor\Guides\Nodes\Inline\ReferenceNode;
use phpDocumentor\Guides\ReferenceResolvers\AnchorNormalizer;
use phpDocumentor\Guides\RestructuredText\Parser\DocumentParserContext;
use Psr\Log\LoggerInterface;

final class CaseTextRole extends PhpComponentTextRole
{
    private const TYPE = 'case';

    /**
     * @see https://regex101.com/r/Fj8X5Y/1
     */
    private const CASE_NAME_REGEX = '/^([a-zA-Z0-9_\\\]+)\:\:(\w+)$/';
    public function __construct(
        LoggerInterface                   $logger,
        private readonly AnchorNormalizer $anchorNormalizer,
    ) {
        parent::__construct($logger, $anchorNormalizer);
    }

    protected function createNode(DocumentParserContext $documentParserContext, string $referenceTarget, string|null $referenceName, string $role): ReferenceNode
    {
        if (preg_match(self::INTERLINK_NAME_REGEX, $referenceTarget, $matches)) {
            return $this->createNodeWithInterlink($documentParserContext, $matches[2], $matches[1], $referenceName);
        }
        return $this->createNodeWithInterlink($documentParserContext, $referenceTarget, '', $referenceName);
    }

    private function createNodeWithInterlink(DocumentParserContext $documentParserContext, string $referenceTarget, string $interlinkDomain, string|null $referenceName): ReferenceNode
    {
        if (!preg_match(self::CASE_NAME_REGEX, $referenceTarget, $matches)) {
            $this->logger->warning($referenceTarget . ' is not a valid case name. Use the form "\Vendor\Path\Class::Case".', $documentParserContext->getLoggerInformation());
            $id = $this->anchorNormalizer->reduceAnchor($referenceTarget);
            return new ReferenceNode($id, $referenceName ?? $referenceTarget, $interlinkDomain, 'php:' . $this->getName());
        }

        $class = $matches[1];
        $const = $matches[2];

        $id = $this->anchorNormalizer->reduceAnchor($class . '::' . $const);

        return new ReferenceNode($id, $referenceName ?? $referenceTarget, $interlinkDomain, 'php:' . $this->getName());
    }

    public function getName(): string
    {
        return self::TYPE;
    }
}
