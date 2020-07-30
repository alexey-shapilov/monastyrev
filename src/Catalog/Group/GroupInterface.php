<?php

declare(strict_types = 1);

namespace App\Catalog\Group;

use App\Catalog\Product;

interface GroupInterface
{
    public function getId(): int;

    public function getName(): string;

    public function getParent(): ?GroupInterface;

    public function addChild(GroupInterface $child): void;

    public function getChildren(): array;

    public function setParent(?GroupInterface $parent): void;

    public function getDescriptionFormat(): ?string;

    public function isInheritDescription(): bool;

    public function renderProduct(Product $product): string;
}
