<?php

declare(strict_types = 1);

namespace App\Catalog;

final class Product
{
    private int $id;

    private int $groupId;

    private string $name;

    private int $price;

    private Meta $meta;

    public function __construct(
        int $id,
        int $groupId,
        string $name,
        int $price,
        Meta $meta
    )
    {
        $this->id = $id;
        $this->groupId = $groupId;
        $this->name = $name;
        $this->price = $price;
        $this->meta = $meta;
    }

    /**
     * @param $name
     *
     * @return mixed|null
     */
    public function field($name)
    {
        if (property_exists(self::class, $name)) {
            return $this->{$name};
        }

        return $this->meta->getMeta($name) ?? null;
    }
}
