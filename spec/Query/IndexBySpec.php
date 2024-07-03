<?php

namespace spec\Purist\Specification\Doctrine\Query;

use Doctrine\ORM\QueryBuilder;
use PhpSpec\ObjectBehavior;

class IndexBySpec extends ObjectBehavior
{
    private string $alias = 'a';

    private string $field = 'foo';

    public function let(): void
    {
        $this->beConstructedWith($this->field, $this->alias);
    }

    public function it_should_modify_query_builder(QueryBuilder $queryBuilder): void
    {
        $property = sprintf('%s.%s', $this->alias, $this->field);

        $queryBuilder->indexBy($this->alias, $property)->shouldBeCalled()->willReturn($queryBuilder);

        $this->isSatisfiedBy('foo')->shouldReturn(true);
        $this->modify($queryBuilder, $this->alias);
    }
}
