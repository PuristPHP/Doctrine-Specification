<?php

namespace Purist\Specification\Doctrine;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\QueryBuilder;
use Purist\Specification\Doctrine\Exception\InvalidArgumentException;

/**
 * Specification can be used as a quick-start to writing your own specifications.
 * It extends Doctrines ArrayCollection class, so you can compose specifications.
 *
 * @extends ArrayCollection<int, SpecificationInterface>
 */
class Specification extends ArrayCollection implements SpecificationInterface
{
    public const string AND_X = 'andX';
    public const string OR_X = 'orX';
    /**
     * @var array<string>
     */
    protected static array $types = [self::OR_X, self::AND_X];
    private string $type = self::AND_X;

    /**
     * @param SpecificationInterface[] $elements
     */
    public function __construct(array $elements = [])
    {
        parent::__construct();

        $this->setChildren($elements);
    }

    /**
     * @param SpecificationInterface $value
     *
     * @throws InvalidArgumentException
     */
    #[\Override]
    public function add(mixed $value): void
    {
        if (!$value instanceof SpecificationInterface) {
            throw new InvalidArgumentException(sprintf('"%s" does not implement "%s"!', (is_object($value)) ? $value::class : $value, SpecificationInterface::class));
        }

        parent::add($value);
    }

    #[\Override]
    public function modify(QueryBuilder $queryBuilder, ?string $dqlAlias = null): ?string
    {
        $match = static fn (SpecificationInterface $specification): ?string => $specification->modify($queryBuilder, $dqlAlias);

        $result = array_filter(array_map($match, $this->toArray()));
        if ([] === $result) {
            return null;
        }

        return $queryBuilder->expr()->{$this->type}(...$result);
    }

    #[\Override]
    public function isSatisfiedBy(mixed $value): bool
    {
        /** @var SpecificationInterface $child */
        foreach ($this as $child) {
            if ($child->isSatisfiedBy($value)) {
                continue;
            }

            return false;
        }

        return true;
    }

    /**
     * @param SpecificationInterface[] $children
     */
    protected function setChildren(array $children): static
    {
        $this->clear();
        array_map($this->add(...), $children);

        return $this;
    }

    /**
     * Set the type of comparison.
     *
     * @throws InvalidArgumentException
     */
    protected function setType(string $type): static
    {
        if (!in_array($type, self::$types, true)) {
            $message = sprintf('"%s" is not a valid type! Valid types: %s', $type, implode(', ', self::$types));
            throw new InvalidArgumentException($message);
        }

        $this->type = $type;

        return $this;
    }
}
