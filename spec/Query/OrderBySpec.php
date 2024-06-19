<?php

namespace spec\Purist\Specification\Doctrine\Query;

use Doctrine\ORM\QueryBuilder;
use PhpSpec\ObjectBehavior;
use Purist\Specification\Doctrine\Exception\InvalidArgumentException;
use Purist\Specification\Doctrine\Query\OrderBy;

class OrderBySpec extends ObjectBehavior
{
    private string $alias = 'a';

    private string $field = 'foo';

    private string $order = OrderBy::ASC;

    public function let(): void
    {
        $this->beConstructedWith($this->field, $this->order, $this->alias);
    }

    public function it_should_throw_exception_when_given_invalid_order(): void
    {
        $this->shouldThrow(InvalidArgumentException::class)
            ->during('__construct', ['foo', 'bar']);
    }

    public function it_should_modify_query_builder(QueryBuilder $queryBuilder): void
    {
        $sort = sprintf('%s.%s', $this->alias, $this->field);

        $queryBuilder->addOrderBy($sort, $this->order)->shouldBeCalled()->willReturn($queryBuilder);

        $this->isSatisfiedBy('foo')->shouldReturn(true);
        $this->modify($queryBuilder, $this->alias);
    }
}
