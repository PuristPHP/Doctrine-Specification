<?php

namespace Purist\Specification\Doctrine\Condition;

use Purist\Specification\Doctrine\Exception\InvalidArgumentException;

class GreaterThan extends Comparison
{
    /**
     * @throws InvalidArgumentException
     */
    public function __construct(string $field, string $value, ?string $dqlAlias = null)
    {
        parent::__construct(self::GT, $field, $value, $dqlAlias);
    }
}
