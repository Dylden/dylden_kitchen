<?php

namespace App\service;

use PHPUnit\Framework\TestCase;

class UniqueFilenameGeneratorTest extends TestCase
{
    public function testUniqueFilenameGenerator()
    {
        $uniqueFilenameGenerator = new UniqueFilenameGenerator();
        $UniqueFileName = $uniqueFilenameGenerator->generateUniqueFilename('hello', 'jpeg');

        $this->assertStringContainsString('jpeg', $UniqueFileName);
    }
}