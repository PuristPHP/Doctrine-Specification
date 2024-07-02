<?php

namespace Purist\Specification\Doctrine\Query;

use Doctrine\ORM\QueryBuilder;
use Purist\Specification\Doctrine\Exception\InvalidArgumentException;
use Purist\Specification\Doctrine\SpecificationInterface;

/**
 * @author Kyle Tucker <kyleatucker@gmail.com>
 */
readonly class Having implements SpecificationInterface
{
    public const string HAVING = 'having';
    public const string AND_HAVING = 'andHaving';
    public const string OR_HAVING = 'orHaving';
    /**
     * @var array<string>
     */
    protected const array TYPES = [self::HAVING, self::AND_HAVING, self::OR_HAVING];

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(protected SpecificationInterface $specification, protected string $type = self::AND_HAVING)
    {
        if (!in_array($type, self::TYPES, true)) {
            throw new InvalidArgumentException(sprintf('"%s" is not a valid type! Valid types: %s', $type, implode(', ', self::TYPES)));
        }
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
    public function setType(string $type): self
    {
        return new self($this->specification, $type);
    }
}
