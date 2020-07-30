<?php

declare(strict_types = 1);

namespace App\Catalog;

final class Meta
{
    private array $meta = [];

    public function __construct(?array $headMeta, ?array $meta)
    {
        if ($meta) {
            foreach ($meta as $index => $value) {
                $this->meta[$headMeta[$index]] = $value;
            }
        }
    }

    /**
     * @param string $name
     *
     * @return mixed|null
     */
    public function getMeta(string $name)
    {
        return $this->meta[$name] ?? null;
    }
}
