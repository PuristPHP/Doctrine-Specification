<?php

namespace Purist\Specification\Doctrine\Condition;

use Doctrine\ORM\QueryBuilder;
use Purist\Specification\Doctrine\AbstractSpecification;

class Between extends AbstractSpecification
{
    public function __construct(string $field, protected $from, protected $to, ?string $dqlAlias = null)
    {
        parent::__construct($field, $dqlAlias);
    }

    #[\Override]
    public function modify(QueryBuilder $queryBuilder, ?string $dqlAlias = null): string
    {
        $fromParam = $this->generateParameterName('from', $queryBuilder);
        $toParam = $this->generateParameterName('to', $queryBuilder);

        $queryBuilder->setParameter($fromParam, $this->from);
        $queryBuilder->setParameter($toParam, $this->to);

        return $queryBuilder->expr()->between(
            $this->createPropertyWithAlias($dqlAlias),
            sprintf(':%s', $fromParam),
            sprintf(':%s', $toParam),
        );
    }

    private function generateParameterName(string $type, QueryBuilder $queryBuilder): string
    {
        return sprintf('%s_%d', $type, count($queryBuilder->getParameters()));
    }
}
