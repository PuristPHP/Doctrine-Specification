<?php

declare(strict_types=1);

namespace Purist\Specification\Doctrine\Query;

use Doctrine\ORM\QueryBuilder;
use Purist\Specification\Doctrine\Exception\InvalidArgumentException;
use Purist\Specification\Doctrine\SpecificationInterface;

/**
 * Select will modify the query-builder, so you can specify SELECT-statements.
 */
readonly class Select implements SpecificationInterface
{
    public const string SELECT = 'select';
    public const string ADD_SELECT = 'addSelect';
    /**
     * @var array<string>
     */
    protected const array TYPES = [self::SELECT, self::ADD_SELECT];

    /**
     * @param string|array<string> $select
     *
     * @throws InvalidArgumentException
     */
    public function __construct(protected string|array $select, private string $type = self::ADD_SELECT)
    {
        if (!in_array($type, self::TYPES, true)) {
            throw new InvalidArgumentException(sprintf('"%s" is not a valid type! Valid types: %s', $type, implode(', ', self::TYPES)));
        }
    }

    #[\Override]
    public function modify(QueryBuilder $queryBuilder, ?string $dqlAlias = null): ?string
    {
        $queryBuilder->{$this->type}($this->select);

        return null;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function setType(string $type): self
    {
        return new self($this->select, $type);
    }

    #[\Override]
    public function isSatisfiedBy(mixed $value): bool
    {
        return true;
    }
}
