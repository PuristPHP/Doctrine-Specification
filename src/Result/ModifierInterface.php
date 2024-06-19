<?php

namespace Purist\Specification\Doctrine\Result;

use Doctrine\ORM\AbstractQuery;

/**
 * Interface ModifierInterface.
 */
interface ModifierInterface
{
    /**
     * Modify the query (e.g. select more fields/relations).
     */
    public function modify(AbstractQuery $query): void;
}
