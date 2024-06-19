<?php

namespace Purist\Specification\Doctrine\Condition;

use Doctrine\ORM\QueryBuilder;

class IsNotNull extends IsNull
{
    #[\Override]
    public function modify(QueryBuilder $queryBuilder, ?string $dqlAlias = null): string
    {
        return $queryBuilder->expr()->isNotNull(
            $this->createPropertyWithAlias($dqlAlias),
        );
    }
}
