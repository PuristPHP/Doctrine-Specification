<?php

namespace spec\Purist\Specification\Doctrine\Condition;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use PhpSpec\ObjectBehavior;
use Purist\Specification\Doctrine\SpecificationInterface;

class BetweenSpec extends ObjectBehavior
{
    private string $field = 'foo';

    private int $from = 1;

    private int $to = 5;

    public function let(): void
    {
        $this->beConstructedWith($this->field, $this->from, $this->to);
    }

    public function it_is_an_expression(): void
    {
        $this->shouldBeAnInstanceOf(SpecificationInterface::class);
    }

    public function it_returns_an_expression_func_object(QueryBuilder $queryBuilder, ArrayCollection $parameters, Expr $expr): void
    {
        $dqlAlias = 'a';
        $expression = 'a.foo between(:from_10, :to_10)';

        $queryBuilder->expr()->willReturn($expr);
        $expr->between(sprintf('%s.%s', $dqlAlias, $this->field), ':from_10', ':to_10')->willReturn($expression);

        $queryBuilder->getParameters()->willReturn($parameters);
        $parameters->count()->willReturn(10);

        $queryBuilder->setParameter('from_10', $this->from)->shouldBeCalled()->willReturn($queryBuilder);
        $queryBuilder->setParameter('to_10', $this->to)->shouldBeCalled()->willReturn($queryBuilder);

        $this->isSatisfiedBy('foo')->shouldReturn(true);
        $this->modify($queryBuilder, $dqlAlias)->shouldReturn($expression);
    }

    public function it_should_use_dql_alias_if_set(QueryBuilder $queryBuilder, ArrayCollection $parameters, Expr $expr): void
    {
        $dqlAlias = 'x';
        $expression = 'x.foo between(:from_10, :to_10)';

        $this->beConstructedWith($this->field, $this->from, $this->to, $dqlAlias);

        $queryBuilder->expr()->willReturn($expr);
        $expr->between(sprintf('%s.%s', $dqlAlias, $this->field), ':from_10', ':to_10')->willReturn($expression);

        $queryBuilder->getParameters()->willReturn($parameters);
        $parameters->count()->willReturn(10);

        $queryBuilder->setParameter('from_10', $this->from)->shouldBeCalled()->willReturn($queryBuilder);
        $queryBuilder->setParameter('to_10', $this->to)->shouldBeCalled()->willReturn($queryBuilder);

        $this->isSatisfiedBy('foo')->shouldReturn(true);
        $this->modify($queryBuilder, $dqlAlias)->shouldReturn($expression);
    }
}
