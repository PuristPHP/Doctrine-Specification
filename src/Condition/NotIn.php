<?php

declare(strict_types=1);

namespace Purist\Specification\Doctrine\Condition;

use Doctrine\ORM\QueryBuilder;
use Purist\Specification\Doctrine\AbstractSpecification;

readonly class NotIn extends AbstractSpecification
{
    public function __construct(string $field, protected mixed $value, ?string $dqlAlias = null)
    {
        parent::__construct($field, $dqlAlias);
    }

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

    protected function generateParameterName(QueryBuilder $queryBuilder): string
    {
        return sprintf('not_in_%d', count($queryBuilder->getParameters()));
    }
}
