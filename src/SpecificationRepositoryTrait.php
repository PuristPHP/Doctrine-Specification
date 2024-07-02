<?php

declare(strict_types=1);

namespace Purist\Specification\Doctrine;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\QueryBuilder;
use Purist\Specification\Doctrine\Exception\LogicException;
use Purist\Specification\Doctrine\Result\ModifierInterface;

trait SpecificationRepositoryTrait
{
    protected string $dqlAlias = 'e';

    /**
     * @see SpecificationAwareInterface::match()
     *
     * @throws LogicException
     */
    public function match(SpecificationInterface $specification, ?ModifierInterface $modifier = null): AbstractQuery
    {
        if (!$specification->isSatisfiedBy($this->getEntityName())) {
            throw new LogicException(sprintf('Specification "%s" not supported by this repository!', $specification::class));
        }

        $queryBuilder = $this->createQueryBuilder($this->dqlAlias);
        $this->modifyQueryBuilder($queryBuilder, $specification);

        return $this->modifyQuery($queryBuilder, $modifier);
    }

    /**
     * Modifies the QueryBuilder according to the passed Specification.
     * Will also set the condition for this query if needed.
     *
     * @internal param string $dqlAlias
     */
    private function modifyQueryBuilder(QueryBuilder $queryBuilder, SpecificationInterface $specification): void
    {
        $condition = $specification->modify($queryBuilder, $this->dqlAlias);

        if (null === $condition || '' === $condition || '0' === $condition) {
            return;
        }

        $queryBuilder->where($condition);
    }

    /**
     * Modifies and returns a Query object according to the (optional) result modifier.
     */
    private function modifyQuery(QueryBuilder $queryBuilder, ?ModifierInterface $modifier = null): AbstractQuery
    {
        $query = $queryBuilder->getQuery();

        if ($modifier instanceof ModifierInterface) {
            $modifier->modify($query);
        }

        return $query;
    }
}
