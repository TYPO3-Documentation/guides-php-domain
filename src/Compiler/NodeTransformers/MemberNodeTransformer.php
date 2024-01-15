<?php

declare(strict_types=1);

namespace T3Docs\GuidesPhpDomain\Compiler\NodeTransformers;

use phpDocumentor\Guides\Compiler\CompilerContext;
use phpDocumentor\Guides\Compiler\NodeTransformer;
use phpDocumentor\Guides\Nodes\Node;

use phpDocumentor\Guides\ReferenceResolvers\AnchorNormalizer;
use Psr\Log\LoggerInterface;
use T3Docs\GuidesPhpDomain\Nodes\PhpComponentNode;
use T3Docs\GuidesPhpDomain\Nodes\PhpMemberNode;

/**
 * @implements NodeTransformer<Node>
 *
 * The "class" directive sets the "classes" attribute value on its content or on the first immediately following
 * non-comment element. https://docutils.sourceforge.io/docs/ref/rst/directives.html#class
 */
class MemberNodeTransformer implements NodeTransformer
{
    private ?PhpComponentNode $currentComponent = null;

    public function __construct(
        private readonly LoggerInterface  $logger,
        private readonly AnchorNormalizer $anchorNormalizer,
    ) {}

    public function enterNode(Node $node, CompilerContext $compilerContext): Node
    {
        if ($node instanceof PhpComponentNode) {
            if ($this->currentComponent instanceof \T3Docs\GuidesPhpDomain\Nodes\PhpComponentNode) {
                $this->logger->warning(
                    sprintf('Nested PHP domain components (php:class, php:interface, php:enum etc) are not supported.
                    Found php:%s inside %s', $node->toString(), $this->currentComponent->toString()),
                    $compilerContext->getLoggerInformation()
                );
            }
            $this->currentComponent = $node;
            return $node;
        }

        if ($node instanceof PhpMemberNode && $this->currentComponent instanceof PhpComponentNode) {
            $newId = $this->anchorNormalizer->reduceAnchor($this->currentComponent->getId() . '::' . $node->getName());
            return $node->withId($newId);
        }

        return $node;
    }

    public function leaveNode(Node $node, CompilerContext $compilerContext): Node|null
    {
        if ($node instanceof PhpComponentNode) {
            $this->currentComponent = null;
        }
        return $node;
    }

    public function supports(Node $node): bool
    {
        return $node instanceof PhpMemberNode || $node instanceof PhpComponentNode;
    }

    public function getPriority(): int
    {
        return 40_000;
    }
}
