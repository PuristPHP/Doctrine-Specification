<?php

namespace Purist\Specification\Doctrine\Query;

use Purist\Specification\Doctrine\Exception\InvalidArgumentException;

class LeftJoin extends Join
{
    /**
     * @throws InvalidArgumentException
     */
    public function __construct(string $field, string $newAlias, ?string $dqlAlias = null)
    {
        parent::__construct($field, $newAlias, $dqlAlias);

        $this->setType(self::LEFT_JOIN);
    }
}
