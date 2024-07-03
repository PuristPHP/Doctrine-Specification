<?php

declare(strict_types=1);

namespace Purist\Specification\Doctrine\Condition;

use Doctrine\ORM\Query\Expr\Comparison as DoctrineComparison;
use Doctrine\ORM\QueryBuilder;
use Purist\Specification\Doctrine\Exception\InvalidArgumentException;

readonly class EqualsProperty extends Comparison
{
    /**
     * @throws InvalidArgumentException
     */
    public function __construct(string $field, string $field2, ?string $dqlAlias = null)
    {
        parent::__construct(self::EQ, $field, $field2, $dqlAlias);
    }

    #[\Override]
    public function modify(QueryBuilder $queryBuilder, ?string $dqlAlias = null): string
    {
        return (string) new DoctrineComparison(
            $this->createPropertyWithAlias($dqlAlias),
            $this->operator,
            $this->createAliasedName($this->value, $dqlAlias),
        );
    }
}
