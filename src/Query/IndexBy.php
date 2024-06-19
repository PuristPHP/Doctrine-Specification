<?php

namespace Purist\Specification\Doctrine\Query;

use Doctrine\ORM\Query\QueryException;
use Doctrine\ORM\QueryBuilder;
use Purist\Specification\Doctrine\AbstractSpecification;

/**
 * IndexBy will modify the query-builder, so you can specify INDEX BY-statements.
 */
class IndexBy extends AbstractSpecification
{
    /**
     * @throws QueryException
     */
    #[\Override]
    public function modify(QueryBuilder $queryBuilder, ?string $dqlAlias = null): ?string
    {
        $queryBuilder->indexBy(
            $this->dqlAlias ?? $dqlAlias,
            $this->createPropertyWithAlias($dqlAlias),
        );

        return null;
    }
}
