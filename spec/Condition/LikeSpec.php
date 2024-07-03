<?php

namespace spec\Purist\Specification\Doctrine\Condition;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\QueryBuilder;
use PhpSpec\ObjectBehavior;
use Purist\Specification\Doctrine\Condition\Like;
use Purist\Specification\Doctrine\SpecificationInterface;

class LikeSpec extends ObjectBehavior
{
    private string $field = 'foo';

    private string $value = 'bar';

    public function let(): void
    {
        $this->beConstructedWith($this->field, $this->value, Like::CONTAINS, 'dqlAlias');
    }

    public function it_is_a_specification(): void
    {
        $this->shouldHaveType(SpecificationInterface::class);
    }

    public function it_surrounds_with_wildcards_when_using_contains(
        QueryBuilder $queryBuilder,
        ArrayCollection $parameters,
    ): void {
        $this->beConstructedWith($this->field, $this->value, Like::CONTAINS, 'dqlAlias');
        $queryBuilder->getParameters()->willReturn($parameters);
        $parameters->count()->willReturn(1);

        $queryBuilder->setParameter('comparison_1', '%bar%')->shouldBeCalled()->willReturn($queryBuilder);

        $this->modify($queryBuilder, null);
    }

    public function it_starts_with_wildcard_when_using_ends_with(
        QueryBuilder $queryBuilder,
        ArrayCollection $parameters,
    ): void {
        $this->beConstructedWith($this->field, $this->value, Like::ENDS_WITH, 'dqlAlias');
        $queryBuilder->getParameters()->willReturn($parameters);
        $parameters->count()->willReturn(1);

        $queryBuilder->setParameter('comparison_1', '%bar')->shouldBeCalled()->willReturn($queryBuilder);

        $this->modify($queryBuilder, null);
    }

    public function it_ends_with_wildcard_when_using_starts_with(
        QueryBuilder $queryBuilder,
        ArrayCollection $parameters,
    ): void {
        $this->beConstructedWith($this->field, $this->value, Like::STARTS_WITH, 'dqlAlias');
        $queryBuilder->getParameters()->willReturn($parameters);
        $parameters->count()->willReturn(1);

        $queryBuilder->setParameter('comparison_1', 'bar%')->shouldBeCalled()->willReturn($queryBuilder);

        $this->modify($queryBuilder, null);
    }
}
