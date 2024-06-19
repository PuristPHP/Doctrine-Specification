<?php

namespace Purist\Specification\Doctrine\Condition;

use Doctrine\ORM\QueryBuilder;

class NotIn extends In
{
    #[\Override]
    public function modify(QueryBuilder $queryBuilder, ?string $dqlAlias = null): string
    {
        $paramName = $this->generateParameterName($queryBuilder);
        $queryBuilder->setParameter($paramName, $this->value);

        return (string) $queryBuilder->expr()->notIn(
            $this->createPropertyWithAlias($dqlAlias),
            sprintf(':%s', $paramName),
        );
    }

    #[\Override]
    protected function generateParameterName(QueryBuilder $queryBuilder): string
    {
        return sprintf('not_in_%d', count($queryBuilder->getParameters()));
    }
}
