<?php

namespace spec\Purist\Specification\Doctrine\Condition;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\QueryBuilder;
use PhpSpec\ObjectBehavior;
use Purist\Specification\Doctrine\SpecificationInterface;

class NotEqualsSpec extends ObjectBehavior
{
    public function let(): void
    {
        $this->beConstructedWith('age', 18, 'a');
    }

    public function it_is_an_expression(): void
    {
        $this->shouldBeAnInstanceOf(SpecificationInterface::class);
    }

    public function it_returns_comparison_object(QueryBuilder $queryBuilder, ArrayCollection $parameters): void
    {
        $queryBuilder->getParameters()->willReturn($parameters);
        $parameters->count()->willReturn(10);

        $queryBuilder->setParameter('comparison_10', 18)->shouldBeCalled()->willReturn($queryBuilder);

        $this->modify($queryBuilder, null)
            ->shouldReturn('a.age <> :comparison_10');
    }

    public function it_uses_comparison_specific_dql_alias_if_passed(
        QueryBuilder $queryBuilder,
        ArrayCollection $parameters,
    ): void {
        $this->beConstructedWith('age', 18, null);

        $queryBuilder->getParameters()->willReturn($parameters);
        $parameters->count()->willReturn(10);

        $queryBuilder->setParameter('comparison_10', 18)->shouldBeCalled()->willReturn($queryBuilder);

        $this->modify($queryBuilder, 'x')->shouldReturn('x.age <> :comparison_10');
    }
}
