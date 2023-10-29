<?php

namespace PhpTui\Docgen;

use Generator;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTextNode;
use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\ConstExprParser;
use PHPStan\PhpDocParser\Parser\PhpDocParser;
use PHPStan\PhpDocParser\Parser\TokenIterator;
use PHPStan\PhpDocParser\Parser\TypeParser;
use PhpTui\Tui\Model\Widget;
use Roave\BetterReflection\BetterReflection;
use Roave\BetterReflection\Reflection\ReflectionClass;
use Roave\BetterReflection\Reflector\DefaultReflector;
use Roave\BetterReflection\SourceLocator\SourceStubber\ReflectionSourceStubber;
use Roave\BetterReflection\SourceLocator\Type\AggregateSourceLocator;
use Roave\BetterReflection\SourceLocator\Type\Composer\Factory\MakeLocatorForComposerJson;
use Roave\BetterReflection\SourceLocator\Type\PhpInternalSourceLocator;
use RuntimeException;

/**
 * Internal library for generating documentation
 */
final class Docgen
{
    /**
     * @param iterable<int,ReflectionClass> $classes
     */
    private function __construct(
        private string $cwd,
        private string $docsDir,
        private iterable $classes,
        private Lexer $lexer,
        private PhpDocParser $parser,
    ) {
    }
    public function generate(): void
    {
        $widgets = [];
        foreach ($this->classes(Widget::class, 'src/**/*.php') as $widget) {
            $node = $this->parsePhpDoc($widget->getDocComment());
            $this->writeTo(sprintf(
                'widgets/%s.md',
                $widget->getShortName()
            ), $this->renderWidget(new WidgetDoc(
                name: lcfirst($widget->getShortName()),
                className: $widget->getName(),
                description: $this->description($node),
                params: []
            )));
        }
    }

    public static function new(string $cwd, string $docsDir): self
    {
        $astLocator = (new BetterReflection())->astLocator();
        $reflector  = new DefaultReflector(new AggregateSourceLocator([
            (new MakeLocatorForComposerJson)($cwd, $astLocator),
            new PhpInternalSourceLocator($astLocator, new ReflectionSourceStubber())
        ]));

        return new self(
            $cwd,
            $docsDir,
            $reflector->reflectAllClasses(),
            new Lexer(),
            (function () {
                $constExpr = new ConstExprParser();
                return new PhpDocParser(new TypeParser($constExpr), $constExpr);
            })(),
        );
    }
    /**
     * @return Generator<ReflectionClass>
     */
    private function classes(string $class, string $glob): Generator
    {
        foreach ((array)glob($this->cwd . '/' . $glob) as $path) {
            $path = realpath((string)$path);
            if (!$path) {
                continue;
            }
            $reflection = $this->reflectFirstClass($path);
            if (null === $reflection) {
                continue;
            }

            if ($reflection->implementsInterface($class)) {
                yield $reflection;
            }
        }
    }

    /**
     * @param non-empty-string $path
     */
    private function reflectFirstClass(string $path): ?ReflectionClass
    {
        foreach ($this->classes as $class) {
            if ($class->getFileName() === $path) {
                return $class;
            }
        }
        return null;
    }

    private function parsePhpDoc(?string $docblock): ?PhpDocNode
    {
        if (null === $docblock) {
            return null;
        }
        $node = $this->parser->parse(new TokenIterator($this->lexer->tokenize($docblock)));
        return $node;
    }

    private function description(?PhpDocNode $node): ?string
    {
        if (null === $node) {
            return null;
        }
        $text = [];
        foreach ($node->children as $child) {
            if ($child instanceof PhpDocTextNode) {
                $text[] = $child->text;
            }
        }
        return implode("\n", $text);
    }

    private function renderWidget(WidgetDoc $widgetDoc): string
    {
        return implode("\n", [
            sprintf('## %s', ucfirst($widgetDoc->name)),
            '',
            $widgetDoc->description,
            '## Example'.
            '',
            'The following code example:',
            '',
            sprintf('{{%% codeInclude file="/data/example/docs/widget/%s.php" language="php" %%}}', $widgetDoc->name),
            '',
            'Should render as:',
            '',
            sprintf('{{%% terminal file="/data/example/docs/widget/%s.snapshot" %%}}', $widgetDoc->name)
        ]);

    }

    private function writeTo(string $subPath, string $content): void
    {
        $path = $this->docsDir . '/' . $subPath;
        $res = file_put_contents($path, $content);
        if (false === $res) {
            throw new RuntimeException(sprintf(
                'Could not write doc file "%s"', $path
            ));
        }
    }
}
