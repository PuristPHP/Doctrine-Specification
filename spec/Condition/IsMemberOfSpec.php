<?php

namespace spec\Purist\Specification\Doctrine\Condition;

use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use PhpSpec\ObjectBehavior;
use Purist\Specification\Doctrine\SpecificationInterface;

class IsMemberOfSpec extends ObjectBehavior
{
    private string $className = '\Foo';
    private string $field = 'foo';
    private string $dqlAlias = 'a';

    public function let(): void
    {
        $this->beConstructedWith($this->field, $this->className, $this->dqlAlias);
    }

    public function it_is_an_expression(): void
    {
        $this->shouldBeAnInstanceOf(SpecificationInterface::class);
    }

    public function it_calls_is_instance_of(QueryBuilder $queryBuilder, Expr $expr): void
    {
        $expression = new Expr\Comparison($this->field, 'member of', $this->className);

        $queryBuilder->expr()->willReturn($expr);
        $expr->isMemberOf(sprintf('%s.%s', $this->dqlAlias, $this->field), $this->className)->willReturn($expression);

        $this->isSatisfiedBy('foo')->shouldReturn(true);
        $this->modify($queryBuilder, 'b')->shouldReturn((string) $expression);
    }

    public function it_uses_dql_alias_if_passed(QueryBuilder $queryBuilder, Expr $expr): void
    {
        $dqlAlias = 'x';
        $aliasedField = sprintf('%s.%s', $dqlAlias, $this->field);
        $expression = new Expr\Comparison($aliasedField, 'member of', $this->className);

        $this->beConstructedWith($this->field, $this->className, null);
        $queryBuilder->expr()->willReturn($expr);

        $expr->isMemberOf($aliasedField, $this->className)->shouldBeCalled()->willReturn($expression);

        $this->isSatisfiedBy('foo')->shouldReturn(true);
        $this->modify($queryBuilder, $dqlAlias)->shouldReturn((string) $expression);
    }
}
