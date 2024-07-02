<?php

namespace Purist\Specification\Doctrine\Condition;

use Doctrine\ORM\Query\Expr\Comparison as DoctrineComparison;
use Doctrine\ORM\QueryBuilder;
use Purist\Specification\Doctrine\AbstractSpecification;
use Purist\Specification\Doctrine\Exception\InvalidArgumentException;

readonly class Comparison extends AbstractSpecification
{
    public const string EQ = '=';
    public const string NEQ = '<>';
    public const string LT = '<';
    public const string LTE = '<=';
    public const string GT = '>';
    public const string GTE = '>=';
    public const string LIKE = 'LIKE';
    /**
     * @var string[]
     */
    protected const array OPERATORS = [self::EQ, self::NEQ, self::LT, self::LTE, self::GT, self::GTE, self::LIKE];

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(protected string $operator, string $field, protected string $value, ?string $dqlAlias = null)
    {
        if (!in_array($operator, self::OPERATORS, true)) {
            throw new InvalidArgumentException(sprintf('"%s" is not a valid operator. Valid operators: %s', $operator, implode(', ', self::OPERATORS)));
        }

        parent::__construct($field, $dqlAlias);
    }

    /**
     * Return a string expression which can be used as condition (in WHERE-clause).
     */
    #[\Override]
    public function modify(QueryBuilder $queryBuilder, ?string $dqlAlias = null): string
    {
        $paramName = $this->generateParameterName($queryBuilder);
        $queryBuilder->setParameter($paramName, $this->value);

        return (string) new DoctrineComparison(
            $this->createPropertyWithAlias($dqlAlias),
            $this->operator,
            sprintf(':%s', $paramName),
        );
    }

    /**
     * Return automatically generated parameter name.
     */
    protected function generateParameterName(QueryBuilder $queryBuilder): string
    {
        return sprintf('comparison_%d', count($queryBuilder->getParameters()));
    }
}
