<?php

namespace spec\Purist\Specification\Doctrine\Result;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\Query;
use PhpSpec\ObjectBehavior;
use Purist\Specification\Doctrine\Result\ModifierInterface;

class AsArraySpec extends ObjectBehavior
{
    public function it_is_a_result_modifier(): void
    {
        $this->shouldHaveType(ModifierInterface::class);
    }

    public function it_sets_hydration_mode_to_array(AbstractQuery $query): void
    {
        $query->setHydrationMode(Query::HYDRATE_ARRAY)->shouldBeCalled()->willReturn($query);

        $this->modify($query);
    }
}
