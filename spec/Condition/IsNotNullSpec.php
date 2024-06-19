<?php

namespace spec\Purist\Specification\Doctrine\Condition;

use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use PhpSpec\ObjectBehavior;
use Purist\Specification\Doctrine\SpecificationInterface;

class IsNotNullSpec extends ObjectBehavior
{
    private string $field = 'foo';

    private string $dqlAlias = 'a';

    public function let(): void
    {
        $this->beConstructedWith($this->field, $this->dqlAlias);
    }

    public function it_is_an_expression(): void
    {
        $this->shouldBeAnInstanceOf(SpecificationInterface::class);
    }

    public function it_calls_not_null(QueryBuilder $queryBuilder, Expr $expr): void
    {
        $expression = 'a.foo is not null';

        $queryBuilder->expr()->willReturn($expr);
        $expr->isNotNull(sprintf('%s.%s', $this->dqlAlias, $this->field))->willReturn($expression);

        $this->modify($queryBuilder, null)->shouldReturn($expression);
    }

    public function it_uses_dql_alias_if_passed(QueryBuilder $queryBuilder, Expr $expr): void
    {
        $dqlAlias = 'x';
        $expression = 'x.foo is not null';

        $this->beConstructedWith($this->field, null);

        $queryBuilder->expr()->willReturn($expr);
        $expr->isNotNull(sprintf('%s.%s', $dqlAlias, $this->field))->willReturn($expression);

        $this->modify($queryBuilder, $dqlAlias)->shouldReturn($expression);
    }
}
