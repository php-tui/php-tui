<?php

declare(strict_types=1);

namespace PhpTui\Docgen;

use Closure;
use Generator;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTextNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\VarTagValueNode;
use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\ConstExprParser;
use PHPStan\PhpDocParser\Parser\PhpDocParser;
use PHPStan\PhpDocParser\Parser\TokenIterator;
use PHPStan\PhpDocParser\Parser\TypeParser;
use PhpTui\Docgen\Renderer\AggregateDocRenderer;
use PhpTui\Tui\Model\Canvas\Shape;
use PhpTui\Tui\Model\Widget;
use Roave\BetterReflection\BetterReflection;
use Roave\BetterReflection\Reflection\ReflectionClass;
use Roave\BetterReflection\Reflection\ReflectionProperty;
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
     * @param DocUnitConfig[] $unitConfigs
     */
    public function __construct(
        private readonly string $docsDir,
        private readonly iterable $classes,
        private readonly Lexer $lexer,
        private readonly PhpDocParser $parser,
        private readonly array $unitConfigs,
        private readonly DocRenderer $renderer,
    ) {
    }

    public function generate(): void
    {
        foreach ($this->unitConfigs as $unitConfig) {
            $this->render($unitConfig);
        }
    }

    /**
     * @param DocUnitConfig[] $unitConfigs
     */
    public static function new(string $cwd, string $docsDir, array $unitConfigs, DocRenderer $renderer): self
    {
        $astLocator = (new BetterReflection())->astLocator();
        $reflector  = new DefaultReflector(new AggregateSourceLocator([
            (new MakeLocatorForComposerJson())($cwd, $astLocator),
            new PhpInternalSourceLocator($astLocator, new ReflectionSourceStubber())
        ]));

        return new self(
            $docsDir,
            $reflector->reflectAllClasses(),
            new Lexer(),
            (function (): PhpDocParser {
                $constExpr = new ConstExprParser();

                return new PhpDocParser(new TypeParser($constExpr), $constExpr);
            })(),
            $unitConfigs,
            $renderer,
        );
    }
    private function render(
        DocUnitConfig $config
    ): void
    {
        foreach ($this->classes($config->className, 'src/**/*.php') as $widget) {
            $node = $this->parsePhpDoc($widget->getDocComment());
            $this->writeTo(sprintf(
                '%s/%s.md',
                $config->outPath,
                $widget->getShortName()
            ), $this->renderer->render(new AggregateDocRenderer(), new DocClass(
                name: lcfirst($widget->getShortName()),
                humanName: $this->humanName($widget->getShortName(), $config->stripSuffix),
                className: $widget->getName(),
                singular: $config->singular,
                description: $this->description($node),
                params: array_values(array_filter(array_map(function (ReflectionProperty $prop): false|WidgetParam {
                    if (false === $prop->isPromoted()) {
                        return false;
                    }
                    $phpType = $prop->getType() ? $prop->getType()->__toString() : '';
                    $phpDoc = $this->parsePhpDoc($prop->getDocComment());
                    $type = null;
                    if ($phpDoc) {
                        $type = implode(
                            '|',
                            array_map(
                                static fn (VarTagValueNode $node): string => $node->__toString(),
                                $phpDoc->getVarTagValues()
                            )
                        );
                    }

                    return new WidgetParam(
                        type: $type ? $type : $phpType,
                        name: $prop->getName(),
                        description: $this->description($phpDoc),
                    );
                }, $widget->getProperties()))),
            )) ?? throw new RuntimeException('Aggregate renderer should always return a string'));
        }
    }

    /**
     * @return Generator<ReflectionClass>
     */
    private function classes(string $class, string $glob): Generator
    {
        foreach ($this->classes as $reflection) {
            if ($reflection->implementsInterface($class)) {
                yield $reflection;
            }
        }
    }

    private function parsePhpDoc(?string $docblock): ?PhpDocNode
    {
        if (null === $docblock) {
            return null;
        }

        return $this->parser->parse(new TokenIterator($this->lexer->tokenize($docblock)));
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

        return str_replace("\n", '', implode(' ', $text));
    }


    private function writeTo(string $subPath, string $content): void
    {
        $path = $this->docsDir . '/' . $subPath;
        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 511, true);
        }
        $res = file_put_contents($path, $content);
        if (false === $res) {
            throw new RuntimeException(sprintf(
                'Could not write doc file "%s"',
                $path
            ));
        }
    }

    private function humanName(string $subject, ?string $suffix): string
    {
        $replaced = preg_replace('{([A-Z])}', ' \1', ucfirst($subject));
        if (null === $replaced) {
            throw new RuntimeException('Could not replace');
        }
        if ($suffix !== null) {
            if ($pos = strrpos($replaced, $suffix)) {
                $replaced = substr($replaced, 0, $pos);
            }
        }

        return $replaced;
    }
}
