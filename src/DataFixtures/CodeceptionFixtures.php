<?php

namespace App\DataFixtures;

class CodeceptionFixtures extends Fixture
{
    public function __construct(string $dataPath)
    {
        parent::__construct($dataPath);
        $this->filePath = '/codeception/beauporientation_test_codeception.sql';
    }
}
