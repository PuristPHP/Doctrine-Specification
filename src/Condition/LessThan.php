<?php

namespace Purist\Specification\Doctrine\Condition;

use Purist\Specification\Doctrine\Exception\InvalidArgumentException;

class LessThan extends Comparison
{
    /**
     * @throws InvalidArgumentException
     */
    public function __construct(string $field, string $value, ?string $dqlAlias = null)
    {
        parent::__construct(self::LT, $field, $value, $dqlAlias);
    }
}
