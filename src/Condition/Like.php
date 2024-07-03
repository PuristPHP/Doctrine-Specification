<?php

declare(strict_types=1);

namespace Purist\Specification\Doctrine\Condition;

use Purist\Specification\Doctrine\Exception\InvalidArgumentException;

readonly class Like extends Comparison
{
    public const string CONTAINS = '%%%s%%';
    public const string ENDS_WITH = '%%%s';
    public const string STARTS_WITH = '%s%%';

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(string $field, string $value, string $format = self::CONTAINS, ?string $dqlAlias = null)
    {
        $formattedValue = $this->formatValue($format, $value);
        parent::__construct(self::LIKE, $field, $formattedValue, $dqlAlias);
    }

    private function formatValue(string $format, string $value): string
    {
        return sprintf($format, $value);
    }
}
