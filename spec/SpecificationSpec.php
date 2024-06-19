<?php

namespace spec\Purist\Specification\Doctrine;

use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use PhpSpec\ObjectBehavior;
use Purist\Specification\Doctrine\Exception\InvalidArgumentException;
use Purist\Specification\Doctrine\SpecificationInterface;

class SpecificationSpec extends ObjectBehavior
{
    public function it_is_a_specification(): void
    {
        $this->shouldHaveType(SpecificationInterface::class);
    }

    public function it_modifies_all_child_queries(
        QueryBuilder $queryBuilder,
        SpecificationInterface $specificationA,
        SpecificationInterface $specificationB,
    ): void {
        $this->beConstructedWith([$specificationA, $specificationB]);
        $dqlAlias = 'a';

        $specificationA->modify($queryBuilder, $dqlAlias)->shouldBeCalled();
        $specificationB->modify($queryBuilder, $dqlAlias)->shouldBeCalled();

        $this->modify($queryBuilder, $dqlAlias);
    }

    public function it_supports_conditions(
        QueryBuilder $queryBuilder,
        Expr $expr,
        SpecificationInterface $conditionA,
        SpecificationInterface $conditionB,
    ): void {
        $dqlAlias = 'a';
        $x = 'x';
        $y = 'y';
        $expression = new Expr\Andx([$x, $y]);

        $this[] = $conditionA;
        $this[] = $conditionB;

        $conditionA->isSatisfiedBy('foo')->willReturn(true);
        $conditionB->isSatisfiedBy('foo')->willReturn(true);

        $conditionA->modify($queryBuilder, $dqlAlias)->willReturn($x);
        $conditionB->modify($queryBuilder, $dqlAlias)->willReturn($y);
        $queryBuilder->expr()->willReturn($expr);

        $expr->andX($x, $y)->shouldBeCalled()->willReturn($expression);

        $this->isSatisfiedBy('foo')->shouldReturn(true);
        $this->modify($queryBuilder, $dqlAlias);
    }

    public function it_supports_query_modifiers(
        QueryBuilder $queryBuilder,
        SpecificationInterface $modifierA,
        SpecificationInterface $modifierB,
    ): void {
        $this->beConstructedWith([$modifierA, $modifierB]);

        $dqlAlias = 'a';

        $modifierA->isSatisfiedBy('foo')->willReturn(true);
        $modifierB->isSatisfiedBy('foo')->willReturn(true);

        $modifierA->modify($queryBuilder, $dqlAlias)->shouldBeCalled();
        $modifierB->modify($queryBuilder, $dqlAlias)->shouldBeCalled();

        $this->isSatisfiedBy('foo')->shouldReturn(true);
        $this->modify($queryBuilder, $dqlAlias)->shouldReturn(null);
    }

    public function it_should_throw_exception_when_child_does_not_support_class(
        SpecificationInterface $specificationA,
        SpecificationInterface $specificationB,
    ): void {
        $className = 'foo';
        $this->beConstructedWith([$specificationA, $specificationB]);

        $specificationA->isSatisfiedBy($className)->willReturn(true);
        $specificationB->isSatisfiedBy($className)->willReturn(false);

        $this->isSatisfiedBy($className)->shouldReturn(false);
    }

    public function it_should_throw_exception_on_invalid_child(): void
    {
        $this->shouldThrow(InvalidArgumentException::class)
            ->during('add', ['bar']);
    }
}
