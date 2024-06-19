<?php

namespace spec\Purist\Specification\Doctrine\Query;

use Doctrine\ORM\Query\Expr\Join as DoctrineJoin;
use Doctrine\ORM\QueryBuilder;
use PhpSpec\ObjectBehavior;
use Purist\Specification\Doctrine\Exception\InvalidArgumentException;
use Purist\Specification\Doctrine\Query\Join;
use Purist\Specification\Doctrine\SpecificationInterface;

class JoinSpec extends ObjectBehavior
{
    public function let(): void
    {
        $this->beConstructedWith('user', 'authUser', 'a');
    }

    public function it_is_a_specification(): void
    {
        $this->shouldHaveType(SpecificationInterface::class);
    }

    public function it_should_support_anything(): void
    {
        $this->isSatisfiedBy('foo')->shouldReturn(true);
    }

    public function it_joins_with_default_dql_alias(QueryBuilder $queryBuilder): void
    {
        $queryBuilder->join('a.user', 'authUser', null, null, null)->shouldBeCalled()->willReturn($queryBuilder);
        $this->modify($queryBuilder, 'a');
    }

    public function it_uses_local_alias_if_global_was_not_set(QueryBuilder $queryBuilder): void
    {
        $this->beConstructedWith('user', 'authUser');
        $queryBuilder->join('b.user', 'authUser', null, null, null)->shouldBeCalled()->willReturn($queryBuilder);
        $this->modify($queryBuilder, 'b');
    }

    public function it_should_use_be_able_to_use_join_conditions(QueryBuilder $queryBuilder): void
    {
        $joinType = DoctrineJoin::ON;
        $joinCondition = 'join condition';

        $this->beConstructedWith('user', 'authUser');

        $this->setConditionType($joinType)->shouldReturn($this);
        $this->setCondition($joinCondition)->shouldReturn($this);

        $queryBuilder->join('a.user', 'authUser', $joinType, $joinCondition, null)->shouldBeCalled()->willReturn($queryBuilder);

        $this->modify($queryBuilder, 'a');
    }

    public function it_should_be_able_to_set_index_by_for_join(QueryBuilder $queryBuilder): void
    {
        $indexedBy = 'index_by';

        $this->beConstructedWith('user', 'authUser');

        $queryBuilder->join('a.user', 'authUser', null, null, $indexedBy)->shouldBeCalled()->willReturn($queryBuilder);

        $this->setIndexedBy($indexedBy)->shouldReturn($this);

        $this->modify($queryBuilder, 'a');
    }

    public function it_should_accept_specifications_as_condition(QueryBuilder $queryBuilder, SpecificationInterface $specification): void
    {
        $type = DoctrineJoin::ON;
        $condition = 'condition';

        $this->beConstructedWith('user', 'authUser');

        $specification->modify($queryBuilder, 'a')->willReturn($condition);

        $queryBuilder->join('a.user', 'authUser', $type, $condition, null)->shouldBeCalled()->willReturn($queryBuilder);

        $this->setConditionType($type)->shouldReturn($this);
        $this->setCondition($specification)->shouldReturn($this);
        $this->modify($queryBuilder, 'a');
    }

    public function it_throws_an_exception_when_setting_illegal_type(): void
    {
        $this->setType(Join::LEFT_JOIN);

        $this->shouldThrow(InvalidArgumentException::class)
            ->during('setType', ['foo']);
    }
}
