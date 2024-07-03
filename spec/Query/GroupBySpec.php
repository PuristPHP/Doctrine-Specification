<?php

namespace spec\Purist\Specification\Doctrine\Query;

use Doctrine\ORM\QueryBuilder;
use PhpSpec\ObjectBehavior;
use Purist\Specification\Doctrine\Exception\InvalidArgumentException;
use Purist\Specification\Doctrine\Query\GroupBy;

class GroupBySpec extends ObjectBehavior
{
    private string $field = 'a.foo';

    public function it_should_support_anything(): void
    {
        $this->beConstructedWith($this->field, GroupBy::ADD_GROUP_BY);

        $this->isSatisfiedBy('foo')->shouldReturn(true);
    }

    public function it_calls_groupBy_on_query_builder(QueryBuilder $queryBuilder): void
    {
        $this->beConstructedWith($this->field, GroupBy::GROUP_BY);

        $queryBuilder->groupBy($this->field)->shouldBeCalled()->willReturn($queryBuilder);

        $this->modify($queryBuilder);
    }

    public function it_calls_addGroupBy_on_query_builder(QueryBuilder $queryBuilder): void
    {
        $this->beConstructedWith($this->field, GroupBy::ADD_GROUP_BY);

        $queryBuilder->addGroupBy($this->field)->shouldBeCalled()->willReturn($queryBuilder);

        $this->modify($queryBuilder);
    }

    public function it_throws_exception_when_setting_illegal_type(): void
    {
        $this->beConstructedWith($this->field, 'foo');
        $this->shouldThrow(InvalidArgumentException::class)->duringInstantiation();
    }
}
