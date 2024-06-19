<?php

namespace Purist\Specification\Doctrine;

use Doctrine\ORM\QueryBuilder;

/**
 * SpecificationInterface can be used to implement custom specifications.
 */
interface SpecificationInterface extends \Purist\Specification\SpecificationInterface
{
    public function modify(QueryBuilder $queryBuilder, ?string $dqlAlias = null): ?string;
}
