<?php

namespace Purist\Specification\Doctrine\Condition;

use Doctrine\ORM\QueryBuilder;
use Purist\Specification\Doctrine\AbstractSpecification;

class IsNull extends AbstractSpecification
{
    #[\Override]
    public function modify(QueryBuilder $queryBuilder, ?string $dqlAlias = null): string
    {
        return $queryBuilder->expr()->isNull(
            $this->createPropertyWithAlias($dqlAlias),
        );
    }
}
