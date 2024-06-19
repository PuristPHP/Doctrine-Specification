<?php

namespace Purist\Specification\Doctrine\Result;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\AbstractQuery;
use Purist\Specification\Doctrine\Exception\InvalidArgumentException;

/**
 * CollectionResultModifierInterface allows to compose one/more ResultModifier classes.
 */
class ModifierCollection extends ArrayCollection implements ModifierInterface
{
    /**
     * Compose one or more ResultModifier and evaluate as a single modifier.
     */
    public function __construct(mixed ...$modifiers)
    {
        parent::__construct();

        array_map($this->add(...), $modifiers);
    }

    /**
     * @param ModifierInterface $value
     *
     * @throws InvalidArgumentException
     */
    #[\Override]
    public function add(mixed $value): void
    {
        if (!$value instanceof ModifierInterface) {
            throw new InvalidArgumentException(sprintf('"%s" does not implement "%s"!', (is_object($value)) ? $value::class : $value, ModifierInterface::class));
        }

        parent::add($value);
    }

    /**
     * Modify the query (e.g. select more fields/relations).
     *
     * @throws InvalidArgumentException
     */
    #[\Override]
    public function modify(AbstractQuery $query): void
    {
        foreach ($this as $child) {
            $child->modify($query);
        }
    }
}
