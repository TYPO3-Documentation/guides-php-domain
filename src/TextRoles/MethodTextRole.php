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

final class MethodTextRole extends PhpComponentTextRole
{
    private const TYPE = 'method';

    /**
     * @see https://regex101.com/r/EKNh6v/1
     */
    private const METHOD_NAME_REGEX = '/^([a-zA-Z0-9_\\\]+)\:\:(\w+)(\(.*\)){0,1}$/';
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
        if (!preg_match(self::METHOD_NAME_REGEX, $referenceTarget, $matches)) {
            $this->logger->warning($referenceTarget . ' is not a valid method name. Use the form "\Vendor\Path\Class::method" or "\Vendor\Path\Class::method(int $param)"', $documentParserContext->getLoggerInformation());
            $id = $this->anchorReducer->reduceAnchor($referenceTarget);
            return new ReferenceNode($id, $referenceName ?? $referenceTarget, $interlinkDomain, 'php:' . $this->getName());
        }

        $class = $matches[1];
        $method = $matches[2];

        $id = $this->anchorReducer->reduceAnchor($class . '::' . $method);

        return new ReferenceNode($id, $referenceName ?? $referenceTarget, $interlinkDomain, 'php:' . $this->getName());
    }

    public function getName(): string
    {
        return self::TYPE;
    }
}
