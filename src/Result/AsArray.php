<?php

namespace Purist\Specification\Doctrine\Result;

use Doctrine\ORM\AbstractQuery;

/**
 * Hydrate results as array instead of objects.
 */
readonly class AsArray implements ModifierInterface
{
    /**
     * Modify the query (e.g. select more fields/relations).
     */
    #[\Override]
    public function modify(AbstractQuery $query): void
    {
        $query->setHydrationMode(AbstractQuery::HYDRATE_ARRAY);
    }
}
