<?php

declare(strict_types=1);

namespace Purist\Specification\Doctrine\Condition;

use Purist\Specification\Doctrine\Exception\InvalidArgumentException;

readonly class GreaterThanOrEquals extends Comparison
{
    /**
     * @throws InvalidArgumentException
     */
    public function __construct(string $field, string $value, ?string $dqlAlias = null)
    {
        parent::__construct(self::GTE, $field, $value, $dqlAlias);
    }
}
