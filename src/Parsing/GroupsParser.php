<?php

declare(strict_types = 1);

namespace App\Parsing;

use App\Catalog\Group\DummyGroup;
use App\Catalog\Group\Group;
use App\Catalog\Group\GroupsList;
use App\CsvStream;

final class GroupsParser
{
    private CsvStream $stream;

    public function __construct(string $filePath)
    {
        $this->stream = new CsvStream(fopen($filePath, 'r'));
    }

    public function parse(): GroupsList
    {
        $groups = new GroupsList();

        $header = $this->stream->read();
        while (!$this->stream->eof()) {
            $fieldsGroup = $this->stream->read();
            if ($fieldsGroup) {
                $groupId = !empty($fieldsGroup[2]) ? (int) $fieldsGroup[2] : null;
                $groups->add(
                    new Group(
                        (int) $fieldsGroup[0],
                        $fieldsGroup[1],
                        !empty($fieldsGroup[3]) ? $fieldsGroup[3] : null,
                        boolval($fieldsGroup[4]),
                        $groupId
                            ? ($groups->find($groupId) ?? new DummyGroup($groupId))
                            : null
                    ));
            }
        }
        $groups->fixer();

        return $groups;
    }
}
