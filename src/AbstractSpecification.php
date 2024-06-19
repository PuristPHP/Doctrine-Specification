<?php

namespace Purist\Specification\Doctrine;

abstract class AbstractSpecification implements SpecificationInterface
{
    public function __construct(protected string $field, protected ?string $dqlAlias = null)
    {
    }

    #[\Override]
    public function isSatisfiedBy(mixed $value): bool
    {
        return true;
    }

    /**
     * Create a formatted string for the given property prefixed with the DQL alias.
     */
    protected function createPropertyWithAlias(?string $dqlAlias): string
    {
        return $this->createAliasedName($this->field, $dqlAlias);
    }

    /**
     * Create a formatted string where the value will be prefixed with DQL alias (if not already present).
     */
    protected function createAliasedName(string $value, ?string $dqlAlias): string
    {
        if (str_contains($value, '.')) {
            return $value;
        }

        if (null !== $this->dqlAlias && '' !== $this->dqlAlias && '0' !== $this->dqlAlias) {
            $dqlAlias = $this->dqlAlias;
        }

        return sprintf('%s.%s', $dqlAlias, $value);
    }
}
