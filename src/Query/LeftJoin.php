<?php

namespace Purist\Specification\Doctrine\Query;

use Purist\Specification\Doctrine\Exception\InvalidArgumentException;

readonly class LeftJoin extends Join
{
    /**
     * @throws InvalidArgumentException
     */
    public function __construct(string $field, string $newAlias, ?string $dqlAlias = null)
    {
        parent::__construct($field, $newAlias, $dqlAlias, self::LEFT_JOIN);
    }
}
