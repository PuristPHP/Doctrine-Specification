<?php

namespace Purist\Specification\Doctrine\Query;

use Doctrine\ORM\QueryBuilder;
use Purist\Specification\Doctrine\Exception\InvalidArgumentException;
use Purist\Specification\Doctrine\SpecificationInterface;

/**
 * @author  Kyle Tucker <kyleatucker@gmail.com>
 */
class Having implements SpecificationInterface
{
    public const string HAVING = 'having';
    public const string AND_HAVING = 'andHaving';
    public const string OR_HAVING = 'orHaving';

    protected static array $types = [self::HAVING, self::AND_HAVING, self::OR_HAVING];
    protected string $type;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(protected SpecificationInterface $specification, string $type = self::AND_HAVING)
    {
        $this->setType($type);
    }

    #[\Override]
    public function modify(QueryBuilder $queryBuilder, ?string $dqlAlias = null): ?string
    {
        $queryBuilder->{$this->type}($this->specification->modify($queryBuilder, $dqlAlias));

        return null;
    }

    #[\Override]
    public function isSatisfiedBy(mixed $value): bool
    {
        return true;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function setType(string $type): void
    {
        if (!in_array($type, self::$types, true)) {
            throw new InvalidArgumentException(sprintf('"%s" is not a valid type! Valid types: %s', $type, implode(', ', self::$types)));
        }

        $this->type = $type;
    }
}
