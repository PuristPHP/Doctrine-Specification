<?php

namespace spec\Purist\Specification\Doctrine\Logic;

use Doctrine\Common\Collections\ArrayCollection;
use PhpSpec\ObjectBehavior;
use Purist\Specification\Doctrine\Logic\Composite;
use Purist\Specification\Doctrine\SpecificationInterface;

class AndXSpec extends ObjectBehavior
{
    public function it_should_have_correct_types(): void
    {
        $this->shouldHaveType(SpecificationInterface::class);
        $this->shouldHaveType(Composite::class);
        $this->shouldHaveType(ArrayCollection::class);
    }
}
