<?php

namespace Purist\Specification\Doctrine\Result;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\AbstractQuery;
use Purist\Specification\Doctrine\Exception\InvalidArgumentException;

/**
 * CollectionResultModifierInterface allows to compose one/more ResultModifier classes.
 *
 * @extends ArrayCollection<int, ModifierInterface>
 */
class ModifierCollection extends ArrayCollection implements ModifierInterface
{
    /**
     * Compose one or more ResultModifier and evaluate as a single modifier.
     *
     * @param ModifierInterface ...$modifiers
     *
     * @throws InvalidArgumentException
     */
    public function __construct(mixed ...$modifiers)
    {
        parent::__construct();

        foreach ($modifiers as $modifier) {
            $this->add($modifier);
        }
    }

    /**
     * @param ModifierInterface $element
     *
     * @throws InvalidArgumentException
     */
    #[\Override]
    public function add(mixed $element): void
    {
        if (!$element instanceof ModifierInterface) {
            throw new InvalidArgumentException(sprintf('"%s" does not implement "%s"!', (is_object($element)) ? $element::class : $element, ModifierInterface::class));
        }

        parent::add($element);
    }

    /**
     * Modify the query (e.g. select more fields/relations).
     */
    #[\Override]
    public function modify(AbstractQuery $query): void
    {
        foreach ($this as $child) {
            $child->modify($query);
        }
    }
}
