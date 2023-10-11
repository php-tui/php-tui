<?php

namespace DTL\PhpTui\Adapter\Cassowary;

use DTL\Cassowary\Constraint;
use DTL\Cassowary\Expression;
use DTL\Cassowary\RelationalOperator;
use DTL\Cassowary\Strength;
use DTL\Cassowary\Term;
use DTL\PhpTui\Model\Area;
use DTL\PhpTui\Model\Areas;
use DTL\PhpTui\Model\ConstraintSolver;
use DTL\PhpTui\Model\Constraint\LengthConstraint;
use DTL\PhpTui\Model\Constraint\MaxConstraint;
use DTL\PhpTui\Model\Constraint\MinConstraint;
use DTL\PhpTui\Model\Constraint\PercentageConstraint;
use DTL\PhpTui\Model\Direction;
use DTL\PhpTui\Model\Layout;
use RuntimeException;
use SplObjectStorage;

final class CassowaryConstraintSolver implements ConstraintSolver
{
    public function solve(Layout $layout, Area $area, array $constraints): Areas
    {
        $vars = new SplObjectStorage();
        $elements = array_map(fn () => Element::empty(), $constraints, $constraints);
        $areas = array_map(fn () => Area::empty(), $constraints);
        $destArea = $area->inner($layout->margin);
        foreach ($elements as $i => $element) {
            $vars[$element->x] = [$i, 0];
            $vars[$element->y] = [$i, 1];
            $vars[$element->width] = [$i, 2];
            $vars[$element->height] = [$i, 3];
        }
        $css = [];
        foreach ($elements as $element) {
            $css[] = new Constraint(
                RelationalOperator::GreaterThanOrEqualTo,
                new Expression(
                    [new Term($element->width)],
                    0,
                ),
                Strength::REQUIRED,
            );
            $css[] = new Constraint(
                RelationalOperator::GreaterThanOrEqualTo,
                new Expression(
                    [new Term($element->height)],
                    0,
                ),
                Strength::REQUIRED,
            );
            $css[] = new Constraint(
                RelationalOperator::GreaterThanOrEqualTo,
                new Expression(
                    [new Term($element->left())],
                    $destArea->left(),
                ),
                Strength::REQUIRED,
            );
            $css[] = new Constraint(
                RelationalOperator::GreaterThanOrEqualTo,
                new Expression(
                    [new Term($element->top())],
                    $destArea->top(),
                ),
                Strength::REQUIRED,
            );
            $css[] = new Constraint(
                RelationalOperator::LessThanOrEqualTo,
                $element->right()->assign($destArea->right()),
                Strength::REQUIRED,
            );
            $css[] = new Constraint(
                RelationalOperator::LessThanOrEqualTo,
                $element->bottom()->assign($destArea->bottom()),
                Strength::REQUIRED,
            );
        }

        if (count($elements)) {
            $first = $elements[array_key_first($elements)];
            $css[] = match ($layout->direction) {
                Direction::Vertical => new Constraint(
                    RelationalOperator::Equal,
                    $first->left()->toExpression()->assign($destArea->left()),
                    Strength::REQUIRED
                ),
                Direction::Horizontal => new Constraint(
                    RelationalOperator::Equal,
                    $first->top()->toExpression()->assign($destArea->top()),
                    Strength::REQUIRED
                ),
            };
        }

        if ($layout->expandToFill && count($elements)) {
            $last = $elements[array_key_last($elements)];
            $css[] = match ($layout->direction) {
                Direction::Horizontal => new Constraint(
                    RelationalOperator::Equal,
                    $last->right()->assign($destArea->right()),
                    Strength::REQUIRED
                ),
                Direction::Vertical => new Constraint(
                    RelationalOperator::Equal,
                    $last->bottom()->assign($destArea->bottom()),
                    Strength::REQUIRED
                ),
            };
        }

        match ($layout->direction) {
            Direction::Horizontal => (function () use ($elements, $css, $layout, $destArea) {
                $lastElement = null;
                foreach ($elements as $element) {
                    if (null === $lastElement) {
                        $lastElement = $element;
                        continue;
                    }
                    $css[] = new Constraint(
                        RelationalOperator::Equal,
                        $lastElement->x->add(
                            $lastElement->width
                        )->add(
                            $element->x
                        ),
                        Strength::REQUIRED
                    );
                    $lastElement = $element;
                }

                foreach ($layout->constraints as $i => $constraint) {
                    $css[] = new Constraint(
                        RelationalOperator::Equal,
                        new Expression(
                            [new Term($elements[$i]->y)],
                            (float)$destArea->position->y,
                        ),
                        Strength::REQUIRED
                    );
                    $css[] = new Constraint(
                        RelationalOperator::Equal,
                        new Expression(
                            [new Term($elements[$i]->height)],
                            (float)$destArea->height,
                        ),
                        Strength::REQUIRED
                    );
                    $css[] = match (true) {
                        $constraint instanceof MinConstraint => new Constraint(
                            RelationalOperator::GreaterThanOrEqualTo,
                            $elements[$i]->width->toExpression()->assign($constraint->min),
                            Strength::MEDIUM,
                        ),
                        $constraint instanceof MaxConstraint => new Constraint(
                            RelationalOperator::LessThanOrEqualTo,
                            $elements[$i]->width->toExpression()->assign($constraint->max),
                            Strength::MEDIUM,
                        ),
                        $constraint instanceof PercentageConstraint => new Constraint(
                            RelationalOperator::Equal,
                            $elements[$i]->width->toExpression()->assign($constraint->percentage * $destArea->width / 100.0),
                            Strength::MEDIUM,
                        ),
                        $constraint instanceof LengthConstraint => new Constraint(
                            RelationalOperator::Equal,
                            $elements[$i]->width->toExpression()->assign($constraint->length),
                            Strength::MEDIUM,
                        ),
                        default => throw new RuntimeException(sprintf(
                            'Do not know how to handle constraint: %s', $constraint::class
                        ))
                    };
                }
            })(),
            Direction::Vertical => (function () use ($elements, $css, $layout, $destArea) {
                $lastElement = null;
                foreach ($elements as $element) {
                    if (null === $lastElement) {
                        $lastElement = $element;
                        continue;
                    }
                    $css[] = new Constraint(
                        RelationalOperator::Equal,
                        $lastElement->y->add(
                            $lastElement->width
                        )->add(
                            $element->y
                        ),
                        Strength::REQUIRED
                    );
                    $lastElement = $element;
                }

                foreach ($layout->constraints as $i => $constraint) {
                    $css[] = new Constraint(
                        RelationalOperator::Equal,
                        new Expression(
                            [new Term($elements[$i]->x)],
                            (float)$destArea->position->x,
                        ),
                        Strength::REQUIRED
                    );
                    $css[] = new Constraint(
                        RelationalOperator::Equal,
                        new Expression(
                            [new Term($elements[$i]->width)],
                            (float)$destArea->width,
                        ),
                        Strength::REQUIRED
                    );
                    $css[] = match (true) {
                        $constraint instanceof MinConstraint => new Constraint(
                            RelationalOperator::GreaterThanOrEqualTo,
                            $elements[$i]->height->toExpression()->assign($constraint->min),
                            Strength::MEDIUM,
                        ),
                        $constraint instanceof MaxConstraint => new Constraint(
                            RelationalOperator::LessThanOrEqualTo,
                            $elements[$i]->height->toExpression()->assign($constraint->max),
                            Strength::MEDIUM,
                        ),
                        $constraint instanceof PercentageConstraint => new Constraint(
                            RelationalOperator::Equal,
                            $elements[$i]->height->toExpression()->assign($constraint->percentage * $destArea->height / 100.0),
                            Strength::MEDIUM,
                        ),
                        $constraint instanceof LengthConstraint => new Constraint(
                            RelationalOperator::Equal,
                            $elements[$i]->height->toExpression()->assign($constraint->length),
                            Strength::MEDIUM,
                        ),
                        default => throw new RuntimeException(sprintf(
                            'Do not know how to handle constraint: %s', $constraint::class
                        ))
                    };
                }
            })(),
        };

        return new Areas($areas);
    }
}
