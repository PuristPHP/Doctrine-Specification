<?php

declare(strict_types=1);

namespace Purist\Specification\Doctrine\Query;

use Doctrine\ORM\QueryBuilder;
use Purist\Specification\Doctrine\AbstractSpecification;
use Purist\Specification\Doctrine\Exception\InvalidArgumentException;

/**
 * @author  Kyle Tucker <kyleatucker@gmail.com>
 */
readonly class GroupBy extends AbstractSpecification
{
    public const string GROUP_BY = 'groupBy';
    public const string ADD_GROUP_BY = 'addGroupBy';
    /**
     * @var array<string>
     */
    protected const array TYPES = [self::GROUP_BY, self::ADD_GROUP_BY];

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(string $field, protected string $type = self::ADD_GROUP_BY, ?string $dqlAlias = null)
    {
        parent::__construct($field, $dqlAlias);

        if (!in_array($type, self::TYPES, true)) {
            throw new InvalidArgumentException(sprintf('"%s" is not a valid type! Valid types: %s', $type, implode(', ', self::TYPES)));
        }
    }

    #[\Override]
    public function modify(QueryBuilder $queryBuilder, ?string $dqlAlias = null): ?string
    {
        $queryBuilder->{$this->type}($this->createPropertyWithAlias($dqlAlias));

        return null;
    }
}
