<?php

declare(strict_types=1);

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
    protected const array TYPES = [self::OR_X, self::AND_X];
    private string $type = self::AND_X;

    /**
     * @param SpecificationInterface[] $elements
     *
     * @throws InvalidArgumentException
     */
    public function __construct(array $elements = [])
    {
        parent::__construct();

        $this->setChildren($elements);
    }

    /**
     * @param SpecificationInterface $element
     *
     * @throws InvalidArgumentException
     */
    #[\Override]
    public function add(mixed $element): void
    {
        if (!$element instanceof SpecificationInterface) {
            throw new InvalidArgumentException(sprintf('"%s" does not implement "%s"!', (is_object($element)) ? $element::class : $element, SpecificationInterface::class));
        }

        parent::add($element);
    }

    #[\Override]
    public function modify(QueryBuilder $queryBuilder, ?string $dqlAlias = null): ?string
    {
        $match = static fn (SpecificationInterface $specification): ?string => $specification->modify($queryBuilder, $dqlAlias);

        $result = array_filter(array_map($match, $this->toArray()));
        if ([] === $result) {
            return null;
        }

        return (string) $queryBuilder->expr()->{$this->type}(...$result);
    }

    /**
     * @throws \Exception
     */
    #[\Override]
    public function isSatisfiedBy(mixed $value): bool
    {
        /** @var SpecificationInterface $child */
        foreach ($this->getIterator() as $child) {
            if ($child->isSatisfiedBy($value)) {
                continue;
            }

            return false;
        }

        return true;
    }

    /**
     * @param array<SpecificationInterface> $children
     *
     * @throws InvalidArgumentException
     */
    protected function setChildren(array $children): static
    {
        $this->clear();

        foreach ($children as $child) {
            $this->add($child);
        }

        return $this;
    }

    /**
     * Set the type of comparison.
     *
     * @throws InvalidArgumentException
     */
    protected function setType(string $type): static
    {
        if (!in_array($type, self::TYPES, true)) {
            $message = sprintf('"%s" is not a valid type! Valid types: %s', $type, implode(', ', self::TYPES));
            throw new InvalidArgumentException($message);
        }

        $this->type = $type;

        return $this;
    }
}
