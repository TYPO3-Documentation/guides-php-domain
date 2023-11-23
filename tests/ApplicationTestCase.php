<?php

declare(strict_types=1);

namespace T3Docs\GuidesPhpDomain\Tests;

use phpDocumentor\Guides\Cli\DependencyInjection\ApplicationExtension;
use phpDocumentor\Guides\Cli\DependencyInjection\ContainerFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use T3Docs\GuidesPhpDomain\DependencyInjection\GuidesPhpDomainExtension;

abstract class ApplicationTestCase extends TestCase
{
    private Container|null $container = null;

    public function getContainer(): Container
    {
        if (!$this->container instanceof Container) {
            $this->prepareContainer();
        }

        return $this->container;
    }

    /**
     * @param array<string, array<mixed>> $configuration
     * @param ExtensionInterface $extraExtensions
     *
     * @phpstan-assert Container $this->container
     */
    protected function prepareContainer(string|null $configurationFile = null, array $configuration = [], array $extraExtensions = []): void
    {
        $containerFactory = new ContainerFactory([
            new ApplicationExtension(),
            new GuidesPhpDomainExtension(),
            ...$extraExtensions,
        ]);

        foreach ($configuration as $extension => $extensionConfig) {
            $containerFactory->loadExtensionConfig($extension, $extensionConfig);
        }

        if ($configurationFile !== null) {
            $containerFactory->addConfigFile($configurationFile);
        }

        $this->container = $containerFactory->create(dirname(__DIR__) . '/vendor');
    }
}
