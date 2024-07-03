<?php

namespace spec\Purist\Specification\Doctrine\Result;

use Doctrine\ORM\AbstractQuery;
use PhpSpec\ObjectBehavior;
use Purist\Specification\Doctrine\Exception\InvalidArgumentException;
use Purist\Specification\Doctrine\Result;

class ModifierCollectionSpec extends ObjectBehavior
{
    public function it_should_call_modify_on_child_modifiers(
        Result\ModifierInterface $modifierA,
        Result\ModifierInterface $modifierB,
        AbstractQuery $query,
    ): void {
        $this->beConstructedWith($modifierA, $modifierB);

        $modifierA->modify($query)->shouldBeCalled();
        $modifierB->modify($query)->shouldBeCalled();

        $this->modify($query);
    }

    public function it_should_throw_exception_when_adding_incorrect_children(): void
    {
        $this->shouldThrow(InvalidArgumentException::class)
            ->during('add', ['test']);
    }
}
