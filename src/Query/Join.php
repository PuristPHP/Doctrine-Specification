<?php

namespace Purist\Specification\Doctrine\Query;

use Doctrine\ORM\QueryBuilder;
use Purist\Specification\Doctrine\AbstractSpecification;
use Purist\Specification\Doctrine\Exception\InvalidArgumentException;
use Purist\Specification\Doctrine\SpecificationInterface;

class Join extends AbstractSpecification
{
    public const string JOIN = 'join';
    public const string LEFT_JOIN = 'leftJoin';
    public const string INNER_JOIN = 'innerJoin';

    /**
     * @var array<string>
     */
    protected static array $types = [self::JOIN, self::LEFT_JOIN, self::INNER_JOIN];

    private ?string $conditionType = null;
    private string|SpecificationInterface|null $condition = null;
    private ?string $indexedBy = null;
    private string $type = self::JOIN;

    public function __construct(string $field, private readonly string $newAlias, ?string $dqlAlias = null)
    {
        parent::__construct($field, $dqlAlias);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function setType(string $type): static
    {
        if (!in_array($type, self::$types, true)) {
            throw new InvalidArgumentException(sprintf('"%s" is not a valid type! Valid types: %s', $type, implode(', ', self::$types)));
        }

        $this->type = $type;

        return $this;
    }

    #[\Override]
    public function modify(QueryBuilder $queryBuilder, ?string $dqlAlias = null): ?string
    {
        if (!is_null($this->dqlAlias)) {
            $dqlAlias = $this->dqlAlias;
        }

        $property = $this->createPropertyWithAlias($dqlAlias);

        $condition = $this->condition;
        if ($condition instanceof SpecificationInterface) {
            $condition = $condition->modify($queryBuilder, $dqlAlias);
        }

        $queryBuilder->{$this->type}($property, $this->newAlias, $this->conditionType, $condition, $this->indexedBy);

        return null;
    }

    /**
     * Set the condition type to be used on the join (WITH/ON).
     */
    public function setConditionType(?string $conditionType): static
    {
        $this->conditionType = $conditionType;

        return $this;
    }

    /**
     * Set the condition to be used for the join statement.
     */
    public function setCondition(SpecificationInterface|string|null $condition): static
    {
        $this->condition = $condition;

        return $this;
    }

    /**
     * Set the property which will be used as index for the returned collection.
     */
    public function setIndexedBy(?string $indexedBy): static
    {
        $this->indexedBy = $indexedBy;

        return $this;
    }
}
