<?php

namespace Purist\Specification\Doctrine\Condition;

use Doctrine\ORM\QueryBuilder;
use Purist\Specification\Doctrine\AbstractSpecification;

readonly class IsInstanceOf extends AbstractSpecification
{
    public function __construct(string $field, private string $className, ?string $dqlAlias = null)
    {
        parent::__construct($field, $dqlAlias);
    }

    #[\Override]
    public function modify(QueryBuilder $queryBuilder, ?string $dqlAlias = null): string
    {
        return (string) $queryBuilder->expr()->isInstanceOf(
            $this->createPropertyWithAlias($dqlAlias),
            $this->className,
        );
    }
}
