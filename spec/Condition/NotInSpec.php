<?php

namespace spec\Purist\Specification\Doctrine\Condition;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use PhpSpec\ObjectBehavior;
use Purist\Specification\Doctrine\Condition\NotIn;
use Purist\Specification\Doctrine\SpecificationInterface;

class NotInSpec extends ObjectBehavior
{
    private string $field = 'foo';

    private array $value = ['bar', 'baz'];

    public function let(): void
    {
        $this->beConstructedWith($this->field, $this->value);
    }

    public function it_is_an_expression(): void
    {
        $this->shouldBeAnInstanceOf(SpecificationInterface::class);
        $this->shouldBeAnInstanceOf(NotIn::class);
    }

    public function it_returns_an_expression_func_object(QueryBuilder $queryBuilder, ArrayCollection $parameters, Expr $expr): void
    {
        $dqlAlias = 'a';
        $aliasedField = sprintf('%s.%s', $dqlAlias, $this->field);
        $expression = new Expr\Func(sprintf('%s not in', $aliasedField), ':not_in_10');

        $queryBuilder->expr()->willReturn($expr);
        $expr->notIn($aliasedField, ':not_in_10')->willReturn($expression);

        $queryBuilder->getParameters()->willReturn($parameters);
        $parameters->count()->willReturn(10);

        $queryBuilder->setParameter('not_in_10', $this->value)->shouldBeCalled()->willReturn($queryBuilder);
        $this->isSatisfiedBy('foo')->shouldReturn(true);
        $this->modify($queryBuilder, $dqlAlias)->shouldReturn((string) $expression);
    }

    public function it_should_use_dql_alias_if_set(QueryBuilder $queryBuilder, ArrayCollection $parameters, Expr $expr): void
    {
        $dqlAlias = 'x';
        $aliasedField = sprintf('%s.%s', $dqlAlias, $this->field);
        $expression = new Expr\Func(sprintf('%s not in', $aliasedField), ':not_in_10');

        $this->beConstructedWith($this->field, $this->value, $dqlAlias);

        $queryBuilder->expr()->willReturn($expr);
        $expr->notIn($aliasedField, ':not_in_10')->willReturn($expression);

        $queryBuilder->getParameters()->willReturn($parameters);
        $parameters->count()->willReturn(10);

        $queryBuilder->setParameter('not_in_10', $this->value)->shouldBeCalled()->willReturn($queryBuilder);
        $this->isSatisfiedBy('foo')->shouldReturn(true);
        $this->modify($queryBuilder, $dqlAlias)->shouldReturn((string) $expression);
    }
}
