<?php

namespace Purist\Specification\Doctrine\Query;

use Doctrine\ORM\QueryBuilder;
use Purist\Specification\Doctrine\AbstractSpecification;
use Purist\Specification\Doctrine\Exception\InvalidArgumentException;
use Purist\Specification\Doctrine\SpecificationInterface;

readonly class Join extends AbstractSpecification
{
    public const string JOIN = 'join';
    public const string LEFT_JOIN = 'leftJoin';
    public const string INNER_JOIN = 'innerJoin';
    /**
     * @var array<string>
     */
    protected const array TYPES = [self::JOIN, self::LEFT_JOIN, self::INNER_JOIN];

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(
        string $field,
        private string $newAlias,
        ?string $dqlAlias = null,
        private string $type = self::JOIN,
        private string|SpecificationInterface|null $condition = null,
        private ?string $conditionType = null,
        private ?string $indexedBy = null,
    ) {
        if (!in_array($type, self::TYPES, true)) {
            throw new InvalidArgumentException(sprintf('"%s" is not a valid type! Valid types: %s', $type, implode(', ', self::TYPES)));
        }

        parent::__construct($field, $dqlAlias);
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
     * @throws InvalidArgumentException
     */
    public function setType(string $type): self
    {
        return new self(
            $this->field,
            $this->newAlias,
            $this->dqlAlias,
            $type,
            $this->condition,
            $this->conditionType,
            $this->indexedBy,
        );
    }

    /**
     * Set the condition type to be used on the join (WITH/ON).
     */
    public function setConditionType(?string $conditionType): self
    {
        return new self(
            $this->field,
            $this->newAlias,
            $this->dqlAlias,
            $this->type,
            $this->condition,
            $conditionType,
            $this->indexedBy,
        );
    }

    /**
     * Set the condition to be used for the join statement.
     */
    public function setCondition(SpecificationInterface|string|null $condition): self
    {
        return new self(
            $this->field,
            $this->newAlias,
            $this->dqlAlias,
            $this->type,
            $condition,
            $this->conditionType,
            $this->indexedBy,
        );
    }

    /**
     * Set the property which will be used as index for the returned collection.
     */
    public function setIndexedBy(?string $indexedBy): self
    {
        return new self(
            $this->field,
            $this->newAlias,
            $this->dqlAlias,
            $this->type,
            $this->condition,
            $this->conditionType,
            $indexedBy,
        );
    }
}
