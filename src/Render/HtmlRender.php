<?php

declare(strict_types = 1);

namespace App\Render;

use App\Catalog\Group\GroupInterface;
use App\Catalog\Group\GroupsList;
use App\Catalog\Meta;
use App\Catalog\Product;
use App\Parsing\ProductsByGroup;

final class HtmlRender
{
    private const WRAP_GROUP_PATTERN = "{{tab1}}<li>\n{{tab2}}<h{{level}}>{{header}}</h{{level}}>\n{{tab2}}<ul>\n{{group}}{{tab2}}</ul>\n{{tab1}}</li>\n";

    private const WRAP_ELEMENT_PATTERN = "{{tab}}<li><b>{{element}}</b></li>\n";

    private GroupsList $groups;

    private ProductsByGroup $products;

    private string $tabSymbol;

    public function __construct(
        GroupsList $groups,
        ProductsByGroup $products,
        string $tabSymbol = "\t"
    )
    {
        $this->groups = $groups;
        $this->products = $products;
        $this->tabSymbol = $tabSymbol;
    }

    public function render(): string
    {
        $html = '';

        $groupRoots = $this->groups->getRoots();

        foreach ($groupRoots as $groupRoot) {
            $html .= $this->renderGroup($groupRoot);
        }

        return "<ul>\n" . $html . '</ul>';
    }

    public function renderGroup(GroupInterface $group, int $level = 0, int $tabOffset = 1): string
    {
        $tab = $this->computeTab($tabOffset + 2);

        $elements = $this->renderProducts($group, $tab);

        if ($children = $group->getChildren()) {
            foreach ($children as $child) {
                $elements .= $this->renderGroup($child, $level + 1, $tabOffset + 2);
            }
        }
        $elements = $this->wrapGroup($tabOffset, $level, $group->getName(), $elements);

        return $elements;
    }

    private function renderProducts(GroupInterface $group, string $tab): ?string
    {
        $productsOfGroup = $this->products->find($group->getId());
        $renderedProducts = '';

        if (!$productsOfGroup) {
            return $renderedProducts;
        }

        $header = $productsOfGroup->read();
        while (!$productsOfGroup->eof()) {
            $productFields = $productsOfGroup->read();
            if ($productFields) {
                $productDescription = $group->renderProduct(
                    new Product(
                        (int)$productFields[0],
                        (int)$productFields[1],
                        $productFields[2],
                        (int)$productFields[3],
                        new Meta(array_slice($header, 4), array_slice($productFields, 4))
                    )
                );

                $renderedProducts .= str_replace(
                    [
                        '{{tab}}',
                        '{{element}}',
                    ],
                    [
                        $tab,
                        $productDescription
                    ],
                    self::WRAP_ELEMENT_PATTERN
                );
            }
        }

        return $renderedProducts;
    }

    private function wrapGroup(int $tabOffset, int $level, string $groupName, string $elements)
    {
        $tab1 = $this->computeTab($tabOffset);
        $tab2 = $this->computeTab($tabOffset + 1);
        $tab3 = $this->computeTab($tabOffset + 2);

        return str_replace(
            [
                '{{tab1}}', '{{tab2}}', '{{tab3}}',
                '{{level}}', '{{header}}', '{{group}}',
            ],
            [
                $tab1, $tab2, $tab3,
                $level + 1, $groupName, $elements,
            ],
            self::WRAP_GROUP_PATTERN
        );
    }

    private function computeTab(int $offset): string
    {
        return str_pad('', $offset * strlen($this->tabSymbol), $this->tabSymbol);
    }
}
