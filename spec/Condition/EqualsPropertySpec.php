<?php

namespace spec\Purist\Specification\Doctrine\Condition;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\QueryBuilder;
use PhpSpec\ObjectBehavior;
use Purist\Specification\Doctrine\SpecificationInterface;

class EqualsPropertySpec extends ObjectBehavior
{
    public function let(): void
    {
        $this->beConstructedWith('age', 'address', 'a');
    }

    public function it_is_an_expression(): void
    {
        $this->shouldBeAnInstanceOf(SpecificationInterface::class);
    }

    public function it_returns_comparison_object(QueryBuilder $queryBuilder, ArrayCollection $parameters): void
    {
        $queryBuilder->getParameters()->willReturn($parameters);
        $parameters->count()->willReturn(10);

        $this->modify($queryBuilder, null)
            ->shouldReturn('a.age = a.address');
    }

    public function it_uses_comparison_specific_dql_alias_if_passed(
        QueryBuilder $queryBuilder,
        ArrayCollection $parameters,
    ): void {
        $this->beConstructedWith('age', 'address', null);

        $queryBuilder->getParameters()->willReturn($parameters);
        $parameters->count()->willReturn(10);

        $this->modify($queryBuilder, 'x')->shouldReturn('x.age = x.address');
    }
}
