<?php

namespace spec\Purist\Specification\Doctrine\Logic;

use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use PhpSpec\ObjectBehavior;
use Purist\Specification\Doctrine\SpecificationInterface;

class NotSpec extends ObjectBehavior
{
    public function let(SpecificationInterface $condition): void
    {
        $this->beConstructedWith($condition);
    }

    public function it_calls_parent_match(QueryBuilder $queryBuilder, Expr $expr, SpecificationInterface $condition): void
    {
        $dqlAlias = 'a';
        $aliasedField = sprintf('%s.%s', $dqlAlias, 'foo');
        $parentExpression = new Expr\Func($aliasedField, ':bar');
        $expression = new Expr\Func('NOT', [$parentExpression]);

        $queryBuilder->expr()->willReturn($expr);
        $condition->modify($queryBuilder, $dqlAlias)->willReturn((string) $parentExpression);

        $expr->not($parentExpression)->willReturn($expression);

        $this->modify($queryBuilder, $dqlAlias)->shouldReturn((string) $expression);
    }

    public function it_modifies_parent_query(QueryBuilder $queryBuilder, SpecificationInterface $specification): void
    {
        $dqlAlias = 'a';
        $this->beConstructedWith($specification);

        $specification->modify($queryBuilder, $dqlAlias)->shouldBeCalled()->willReturn(null);
        $this->modify($queryBuilder, $dqlAlias);
    }

    public function it_should_call_supports_on_parent(SpecificationInterface $specification): void
    {
        $className = 'foo';
        $this->beConstructedWith($specification);

        $specification->isSatisfiedBy($className)->shouldBeCalled();

        $this->isSatisfiedBy($className);
    }

    public function it_does_not_modify_parent_query(QueryBuilder $queryBuilder): void
    {
        $this->modify($queryBuilder, 'a');
    }
}
