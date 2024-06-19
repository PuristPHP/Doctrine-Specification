<?php

namespace Purist\Specification\Doctrine\Condition;

use Doctrine\ORM\Query\Expr\Comparison as DoctrineComparison;
use Doctrine\ORM\QueryBuilder;
use Purist\Specification\Doctrine\AbstractSpecification;
use Purist\Specification\Doctrine\Exception\InvalidArgumentException;

class Comparison extends AbstractSpecification
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
    protected static array $operators = [self::EQ, self::NEQ, self::LT, self::LTE, self::GT, self::GTE, self::LIKE];
    protected string $operator;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(string $operator, string $field, protected string $value, ?string $dqlAlias = null)
    {
        if (!in_array($operator, self::$operators, true)) {
            throw new InvalidArgumentException(sprintf('"%s" is not a valid operator. Valid operators: %s', $operator, implode(', ', self::$operators)));
        }

        $this->operator = $operator;

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
