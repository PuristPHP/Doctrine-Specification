<?php

namespace spec\Purist\Specification\Doctrine\Query;

use Doctrine\ORM\QueryBuilder;
use PhpSpec\ObjectBehavior;
use Purist\Specification\Doctrine\Exception\InvalidArgumentException;
use Purist\Specification\Doctrine\Query\Having;
use Purist\Specification\Doctrine\SpecificationInterface;

/**
 * @author  Kyle Tucker <kyleatucker@gmail.com>
 */
class HavingSpec extends ObjectBehavior
{
    private string $dqlAlias = 'a';

    public function let(SpecificationInterface $specification): void
    {
        $this->beConstructedWith($specification);
    }

    public function it_should_support_anything(): void
    {
        $this->isSatisfiedBy('foo')->shouldReturn(true);
    }

    public function it_calls_modify_on_child_specification(QueryBuilder $queryBuilder, SpecificationInterface $specification): void
    {
        $condition = 'foo';
        $specification->modify($queryBuilder, $this->dqlAlias)->shouldBeCalled()->willReturn($condition);
        $queryBuilder->andHaving($condition)->shouldBeCalled()->willReturn($queryBuilder);

        $this->modify($queryBuilder, $this->dqlAlias);
    }

    public function it_calls_having_on_query_builder(QueryBuilder $queryBuilder, SpecificationInterface $specification): void
    {
        $condition = 'foo';
        $specification->modify($queryBuilder, $this->dqlAlias)->willReturn($condition);

        $this->setType(Having::HAVING);
        $queryBuilder->having($condition)->shouldBeCalled()->willReturn($queryBuilder);

        $this->modify($queryBuilder, $this->dqlAlias);
    }

    public function it_calls_andHaving_on_query_builder(QueryBuilder $queryBuilder, SpecificationInterface $specification): void
    {
        $condition = 'foo';
        $specification->modify($queryBuilder, $this->dqlAlias)->willReturn($condition);

        $this->setType(Having::AND_HAVING);
        $queryBuilder->andHaving($condition)->shouldBeCalled()->willReturn($queryBuilder);

        $this->modify($queryBuilder, $this->dqlAlias);
    }

    public function it_calls_orHaving_on_query_builder(QueryBuilder $queryBuilder, SpecificationInterface $specification): void
    {
        $condition = 'foo';
        $specification->modify($queryBuilder, $this->dqlAlias)->willReturn($condition);

        $this->setType(Having::OR_HAVING);
        $queryBuilder->orHaving($condition)->shouldBeCalled()->willReturn($queryBuilder);

        $this->modify($queryBuilder, $this->dqlAlias);
    }

    public function it_throws_exception_when_setting_illegal_type(): void
    {
        $this->shouldThrow(InvalidArgumentException::class)
            ->during('setType', ['foo']);
    }
}
