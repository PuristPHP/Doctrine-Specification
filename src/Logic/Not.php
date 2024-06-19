<?php

namespace Purist\Specification\Doctrine\Logic;

use Doctrine\ORM\QueryBuilder;
use Purist\Specification\Doctrine\SpecificationInterface;

/**
 * Class Not negates whatever specification/filter is passed inside it.
 */
readonly class Not implements SpecificationInterface
{
    public function __construct(private SpecificationInterface $parent)
    {
    }

    #[\Override]
    public function modify(QueryBuilder $queryBuilder, ?string $dqlAlias = null): ?string
    {
        $filter = $this->parent->modify($queryBuilder, $dqlAlias);
        if (null === $filter || '' === $filter || '0' === $filter) {
            return '';
        }

        return (string) $queryBuilder->expr()->not($filter);
    }

    #[\Override]
    public function isSatisfiedBy(mixed $value): bool
    {
        return $this->parent->isSatisfiedBy($value);
    }
}
