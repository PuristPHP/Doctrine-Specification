<?php

namespace spec\Purist\Specification\Doctrine\Logic;

use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use PhpSpec\ObjectBehavior;
use Purist\Specification\Doctrine\Exception\InvalidArgumentException;
use Purist\Specification\Doctrine\Logic\Composite;
use Purist\Specification\Doctrine\SpecificationInterface;

class CompositeSpec extends ObjectBehavior
{
    public const string EXPRESSION = 'andX';

    public function let(SpecificationInterface $specificationA, SpecificationInterface $specificationB): void
    {
        $this->beConstructedWith(self::EXPRESSION, [$specificationA, $specificationB]);
    }

    public function it_is_a_specification(): void
    {
        $this->shouldHaveType(Composite::class);
    }

    public function it_supports_conditions(
        QueryBuilder $queryBuilder,
        Expr $expr,
        SpecificationInterface $conditionA,
        SpecificationInterface $conditionB,
    ): void {
        $this->beConstructedWith(self::EXPRESSION, [$conditionA, $conditionB]);

        $dqlAlias = 'a';
        $x = 'x';
        $y = 'y';
        $expression = new Expr\Andx([$x, $y]);

        $conditionA->isSatisfiedBy('foo')->willReturn(true);
        $conditionB->isSatisfiedBy('foo')->willReturn(true);

        $conditionA->modify($queryBuilder, $dqlAlias)->shouldBeCalled()->willReturn($x);
        $conditionB->modify($queryBuilder, $dqlAlias)->shouldBeCalled()->willReturn($y);
        $queryBuilder->expr()->willReturn($expr);

        $expr->{self::EXPRESSION}($x, $y)->shouldBeCalled()->willReturn($expression);

        $this->isSatisfiedBy('foo')->shouldReturn(true);
        $this->modify($queryBuilder, $dqlAlias)->shouldReturn((string) $expression);
    }

    public function it_should_fail_satisfaction_if_child_fails(
        SpecificationInterface $specificationA,
        SpecificationInterface $specificationB,
    ): void {
        $this->beConstructedWith(self::EXPRESSION, [$specificationA, $specificationB]);

        $specificationA->isSatisfiedBy('foo')->willReturn(true);
        $specificationB->isSatisfiedBy('foo')->willReturn(false);

        $this->isSatisfiedBy('foo')->shouldReturn(false);
    }

    public function it_should_return_null_for_specifications_without_conditions(
        QueryBuilder $queryBuilder,
        Expr $expression,
        SpecificationInterface $specificationA,
        SpecificationInterface $specificationB,
    ): void {
        $this->beConstructedWith(self::EXPRESSION, [$specificationA, $specificationB]);

        $dqlAlias = 'a';

        $specificationA->modify($queryBuilder, $dqlAlias)->willReturn(null);
        $specificationB->modify($queryBuilder, $dqlAlias)->willReturn(null);
        $queryBuilder->expr()->willReturn($expression);

        $this->modify($queryBuilder, $dqlAlias)->shouldReturn(null);
    }

    public function it_should_throw_exception_on_invalid_type(
        SpecificationInterface $specificationA,
        SpecificationInterface $specificationB,
    ): void {
        $type = 'foo';

        $this->beConstructedWith($type, [$specificationA, $specificationB]);

        $this->shouldThrow(InvalidArgumentException::class)
            ->during('__construct', [$type, [$specificationA, $specificationB]]);
    }

    public function it_should_throw_exception_on_invalid_child(): void
    {
        $child = 'bar';

        $this->beConstructedWith(self::EXPRESSION, []);

        $this->shouldThrow(InvalidArgumentException::class)
            ->during('add', [$child]);
    }
}
