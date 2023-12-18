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

final class PropertyTextRole extends PhpComponentTextRole
{
    private const TYPE = 'property';

    /**
     * @see https://regex101.com/r/Fj8X5Y/1
     */
    private const PROPERTY_NAME_REGEX = '/^([a-zA-Z0-9_\\\\]+)\\:\\:\\$?([a-zA-Z0-9_]+)$/';
    public function __construct(
        LoggerInterface $logger,
        private readonly AnchorReducer $anchorReducer,
    ) {
        parent::__construct($logger, $anchorReducer);
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
        if (!preg_match(self::PROPERTY_NAME_REGEX, $referenceTarget, $matches)) {
            $this->logger->warning($referenceTarget . ' is not a valid property name. Use the form "\Vendor\Path\Class::$property".', $documentParserContext->getLoggerInformation());
            $id = $this->anchorReducer->reduceAnchor($referenceTarget);
            return new ReferenceNode($id, $referenceName ?? $referenceTarget, $interlinkDomain, 'php:' . $this->getName());
        }

        $class = $matches[1];
        $const = $matches[2];

        $id = $this->anchorReducer->reduceAnchor($class . '::' . $const);

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
        return [
            'attr',
        ];
    }
}
