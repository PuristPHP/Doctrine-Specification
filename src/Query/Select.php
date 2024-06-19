<?php

namespace Purist\Specification\Doctrine\Query;

use Doctrine\ORM\QueryBuilder;
use Purist\Specification\Doctrine\Exception\InvalidArgumentException;
use Purist\Specification\Doctrine\SpecificationInterface;

/**
 * Select will modify the query-builder, so you can specify SELECT-statements.
 */
class Select implements SpecificationInterface
{
    public const string SELECT = 'select';

    public const string ADD_SELECT = 'addSelect';

    protected static array $types = [self::SELECT, self::ADD_SELECT];

    protected string $type;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(protected string|array $select, string $type = self::ADD_SELECT)
    {
        $this->setType($type);
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
    public function setType(string $type): void
    {
        if (!in_array($type, self::$types, true)) {
            throw new InvalidArgumentException(sprintf('"%s" is not a valid type! Valid types: %s', $type, implode(', ', self::$types)));
        }

        $this->type = $type;
    }

    #[\Override]
    public function isSatisfiedBy(mixed $value): bool
    {
        return true;
    }
}
