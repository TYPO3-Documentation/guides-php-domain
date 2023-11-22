<?php

declare(strict_types=1);

namespace T3Docs\GuidesPhpDomain\Tests\PhpDomain;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use T3Docs\GuidesPhpDomain\Nodes\PhpNamespaceNode;
use T3Docs\GuidesPhpDomain\PhpDomain\FullyQualifiedNameService;
use T3Docs\GuidesPhpDomain\PhpDomain\NamespaceRepository;

final class FullyQualifiedNameServiceTest extends TestCase
{
    private NamespaceRepository&MockObject $namespaceRepository;
    private FullyQualifiedNameService $fullyQualifiedNameService;

    protected function setUp(): void
    {
        $this->namespaceRepository = $this->createMock(NamespaceRepository::class);
        $this->fullyQualifiedNameService = new FullyQualifiedNameService($this->namespaceRepository);
    }

    #[DataProvider('validFqnProvider')]
    public function testValidClassNames(string $expectedName, string|null $expectedNamespace, string $fqn): void
    {
        $result = $this->fullyQualifiedNameService->getFullyQualifiedName($fqn);
        self::assertSame($expectedName, $result->getName());
        self::assertEquals($expectedNamespace, $result->getNamespaceNode()?->getName());
    }

    /**
     * @return array<int, mixed>
     */
    public static function validFqnProvider(): array
    {
        return [
            // Valid FQN with both namespace and class name
            ['YourClassName', 'Your\\Namespace', 'Your\\Namespace\\YourClassName'],

            // Valid FQN with only class name
            ['AnotherClassName', null, 'AnotherClassName'],
        ];
    }

    #[DataProvider('validFqnProviderWithCurrentNamespace')]
    public function testValidClassNameWithCurrentNamespace(string $expectedName, string|null $expectedNamespace, string $fqn, string $currentNamespace): void
    {
        $this->namespaceRepository->method('getCurrentNamespace')->willReturn(new PhpNamespaceNode($currentNamespace));
        $result = $this->fullyQualifiedNameService->getFullyQualifiedName($fqn, true);
        self::assertSame($expectedName, $result->getName());
        self::assertEquals($expectedNamespace, $result->getNamespaceNode()?->getName());
    }

    /**
     * @return array<int, mixed>
     */
    public static function validFqnProviderWithCurrentNamespace(): array
    {
        return [
            // Valid FQN with both namespace and class name
            ['YourClassName', 'Your\\Namespace', 'Your\\Namespace\\YourClassName', 'Another\\Namespace'],

            // Valid FQN with only class name
            ['AnotherClassName', 'Another\\Namespace', 'AnotherClassName', 'Another\\Namespace'],
        ];
    }

    #[DataProvider('inValidFqnProvider')]
    public function testInValidClassNames(string $fqn): void
    {
        $this->expectException(\Exception::class);
        $this->fullyQualifiedNameService->getFullyQualifiedName($fqn);
    }

    /**
     * @return array<int, mixed>
     */
    public static function inValidFqnProvider(): array
    {
        return [
            // Valid FQN with both namespace and class name
            ['Your/Namespace/YourClassName'],

            // Valid FQN with only class name
            [' AnotherClassName'],
        ];
    }
}
