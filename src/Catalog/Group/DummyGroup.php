<?php

declare(strict_types = 1);

namespace App\Catalog\Group;

use App\Catalog\Product;

final class DummyGroup implements GroupInterface
{
    private int $id;

    /**
     * @var array|GroupInterface[]
     */
    private array $children = [];

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return '';
    }

    public function getParent(): ?GroupInterface
    {
        return null;
    }

    public function addChild(GroupInterface $child): void
    {
        $this->children[$child->getId()] = $child;
    }

    /**
     * @return GroupInterface[]|array
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    public function setParent(?GroupInterface $parent): void
    {
    }

    public function getDescriptionFormat(): ?string
    {
        return null;
    }

    public function isInheritDescription(): bool
    {
        return false;
    }

    public function renderProduct(Product $product): string
    {
        return '';
    }
}
