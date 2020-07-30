<?php

declare(strict_types = 1);

namespace App\Catalog\Group;

final class GroupsList
{
    /**
     * @var GroupInterface[]
     */
    public array $groups = [];

    /**
     * @var GroupInterface[]
     */
    private array $roots = [];

    public function add(GroupInterface $group): void
    {
        if (array_key_exists($group->getId(), $this->groups)) {
            throw new \LogicException('This group has already been added');
        }

        $this->groups[$group->getId()] = $group;

        if (!$group->getParent()) {
            $this->roots[$group->getId()] = $group;
        } else {
            $group->getParent()->addChild($group);
        }
    }

    /**
     * @return GroupInterface[]|array
     */
    public function getGroups(): array
    {
        return $this->groups;
    }

    /**
     * @return GroupInterface[]|array
     */
    public function getRoots(): array
    {
        return $this->roots;
    }

    public function find(int $groupId): ?Group
    {
        return $this->groups[$groupId] ?? null;
    }

    public function fixer(): void
    {
        foreach ($this->groups as $group) {
            if (($parent = $group->getParent()) instanceof DummyGroup) {
                $realParent = $this->groups[$parent->getId()];
                $group->setParent($realParent);
                if ($realParent) {
                    foreach ($parent->getChildren() as $child) {
                        $realParent->addChild($child);
                    }
                }
            }
        }
    }
}
