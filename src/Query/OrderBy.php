<?php

namespace Purist\Specification\Doctrine\Query;

use Doctrine\ORM\QueryBuilder;
use Purist\Specification\Doctrine\AbstractSpecification;
use Purist\Specification\Doctrine\Exception\InvalidArgumentException;

readonly class OrderBy extends AbstractSpecification
{
    public const string ASC = 'ASC';
    public const string DESC = 'DESC';
    /**
     * @var array<string>
     */
    private const array VALID_ORDERS = [self::ASC, self::DESC];

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(string $field, private ?string $order = null, ?string $dqlAlias = null)
    {
        $order = null !== $order && '' !== $order && '0' !== $order ? strtoupper($order) : self::ASC;

        if (!in_array($order, self::VALID_ORDERS, true)) {
            throw new InvalidArgumentException();
        }

        parent::__construct($field, $dqlAlias);
    }

    #[\Override]
    public function modify(QueryBuilder $queryBuilder, ?string $dqlAlias = null): ?string
    {
        $queryBuilder->addOrderBy(
            $this->createPropertyWithAlias($dqlAlias),
            $this->order,
        );

        return null;
    }
}
