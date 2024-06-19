<?php

namespace Purist\Specification\Doctrine\Query;

use Doctrine\ORM\QueryBuilder;
use Purist\Specification\Doctrine\AbstractSpecification;
use Purist\Specification\Doctrine\Exception\InvalidArgumentException;

/**
 * @author  Kyle Tucker <kyleatucker@gmail.com>
 */
class GroupBy extends AbstractSpecification
{
    public const string GROUP_BY = 'groupBy';
    public const string ADD_GROUP_BY = 'addGroupBy';

    /** @var string[] */
    protected static array $types = [self::GROUP_BY, self::ADD_GROUP_BY];
    protected string $type;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(string $field, string $type = self::ADD_GROUP_BY, ?string $dqlAlias = null)
    {
        parent::__construct($field, $dqlAlias);

        $this->setType($type);
    }

    #[\Override]
    public function modify(QueryBuilder $queryBuilder, ?string $dqlAlias = null): ?string
    {
        $queryBuilder->{$this->type}($this->createPropertyWithAlias($dqlAlias));

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
}
