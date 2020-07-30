<?php

declare(strict_types = 1);

namespace App\Catalog\Group;

use App\Catalog\Product;

final class Group implements GroupInterface
{
    private int $id;

    private string $name;

    private ?GroupInterface $parent;

    /**
     * @var GroupInterface[]|array
     */
    private array $children = [];

    private ?string $descriptionFormat;

    public bool $inheritDescription;

    public function __construct(
        int $id,
        string $name,
        ?string $descriptionFormat = null,
        bool $inheritDescription = false,
        ?GroupInterface $parent = null
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->descriptionFormat = $descriptionFormat;
        $this->inheritDescription = $inheritDescription;
        $this->parent = $parent;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getParent(): ?GroupInterface
    {
        return $this->parent;
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
        $this->parent = $parent;
    }

    public function getDescriptionFormat(): ?string
    {
        $isParent = func_get_args()[0] ?? null;

        if ($isParent) {
            if ($this->inheritDescription) {
                return $this->descriptionFormat;
            }
        } elseif (null !== $this->descriptionFormat) {
            return $this->descriptionFormat;
        }

        if ($this->isInheritDescription()) {
            return $this->parent->getDescriptionFormat(true);
        }

        return null;
    }

    public function isInheritDescription(): bool
    {
        return $this->parent->inheritDescription ?: $this->parent->isInheritDescription();
    }

    public function renderProduct(Product $product): string
    {
        $tokens = [];
        $description = $this->getDescriptionFormat();
        if (preg_match_all('/%([a-z0-9_-]+)%/i', $description, $tokens)) {
            $replace = [];
            foreach ($tokens[1] as $token) {
                $productField = $product->field($token);

                $replace[] = $productField ?: 'UNDEFINED';
            }
            $description = str_replace($tokens[0], $replace, $description);
        } else {
            $description = $product->field('name');
        };

        return $description;
    }
}
