<?php

namespace spec\Purist\Specification\Doctrine\Query;

use Doctrine\ORM\QueryBuilder;
use PhpSpec\ObjectBehavior;
use Purist\Specification\Doctrine\Exception\InvalidArgumentException;
use Purist\Specification\Doctrine\Query\Select;

class SelectSpec extends ObjectBehavior
{
    public function it_should_add_a_select_to_query_builder(QueryBuilder $queryBuilder): void
    {
        $alias = 'a';
        $entity = 'foo';
        $type = Select::ADD_SELECT;
        $this->beConstructedWith($entity, $type);

        $queryBuilder->addSelect($entity)->shouldBeCalled()->willReturn($queryBuilder);

        $this->isSatisfiedBy('foo')->shouldReturn(true);
        $this->modify($queryBuilder, $alias);
    }

    public function it_should_replace_selects_in_query_builder(QueryBuilder $queryBuilder): void
    {
        $alias = 'a';
        $entity = 'foo';
        $type = Select::SELECT;
        $this->beConstructedWith($entity, $type);

        $queryBuilder->select($entity)->shouldBeCalled()->willReturn($queryBuilder);

        $this->isSatisfiedBy('foo')->shouldReturn(true);
        $this->modify($queryBuilder, $alias);
    }

    public function it_throws_an_exception_when_setting_illegal_type(): void
    {
        $entity = 'foo';
        $this->beConstructedWith($entity);

        $this->setType(Select::ADD_SELECT);

        $this->shouldThrow(InvalidArgumentException::class)
            ->during('setType', ['foo']);
    }
}
