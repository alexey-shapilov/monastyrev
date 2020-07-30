<?php

declare(strict_types = 1);

namespace App\Parsing;

use App\CsvStream;

final class ProductsByGroup
{
    /**
     * @var CsvStream[]
     */
    private array $productsByGroup;

    public function __construct()
    {
        $this->productsByGroup = [];
    }

    public function add(int $groupId, CsvStream $products): void
    {
        $this->productsByGroup[$groupId] = $products;
    }

    public function find(int $groupId): ?CsvStream
    {
        $products = $this->productsByGroup[$groupId] ?? null;
        if ($products) {
            $products->rewind();
        }

        return $products;
    }
}
