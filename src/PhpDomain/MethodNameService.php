<?php

declare(strict_types=1);

namespace T3Docs\GuidesPhpDomain\PhpDomain;

use phpDocumentor\Guides\RestructuredText\Parser\BlockContext;
use Psr\Log\LoggerInterface;
use T3Docs\GuidesPhpDomain\Nodes\MethodNameNode;

class MethodNameService
{
    /**
     * @see https://regex101.com/r/QrrSXk/1
     */
    private const METHOD_SIGNATURE_REGEX = '/^\s*(\w+)\s*\(\s*(.*?)\s*\)\s*(?::\s*(\w+))?\s*$/';

    public function __construct(
        private readonly LoggerInterface $logger
    ) {}

    public function getMethodName(BlockContext $blockContext, string $name): MethodNameNode
    {
        if (preg_match(self::METHOD_SIGNATURE_REGEX, $name, $matches)) {
            $methodName = $matches[1];
            $parameters = $matches[2] ?? '';
            $returnType = $matches[3] ?? null;
            $parametersArray = preg_split('/\s*,\s*/', $parameters, -1, PREG_SPLIT_NO_EMPTY);
            if ($parametersArray === false) {
                $parametersArray = [];
            }
            return new MethodNameNode($methodName, $parametersArray, $returnType);
        }
        $this->logger->warning(sprintf('Method signature %s in PHP-domain method description is invalid. ', $name), $blockContext->getLoggerInformation());
        return new MethodNameNode($name, [], null);
    }
}
