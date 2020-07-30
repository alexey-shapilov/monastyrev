<?php

declare(strict_types = 1);

namespace App\Parsing;

use App\CsvStream;

final class ProductsParser
{
    private CsvStream $stream;

    public function __construct(string $filePath)
    {
        $this->stream = new CsvStream(fopen($filePath, 'r'));
    }

    public function parse(): ProductsByGroup
    {
        $productsByGroup = new ProductsByGroup();

        $header = $this->stream->read();
        while (!$this->stream->eof()) {
            $fieldsProduct = $this->stream->read();
            if ($fieldsProduct) {
                $groupId = (int)$fieldsProduct[1];
                $products = $productsByGroup->find($groupId);
                if (!$products) {
                    $products = new CsvStream(fopen('php://memory', 'r+'));
                    $productsByGroup->add($groupId, $products);
                    $products->write($header);
                }
                $products->write($fieldsProduct);
            }
        }

        return $productsByGroup;
    }
}
