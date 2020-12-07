<?php

namespace BloomAtWork\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class DumbTest extends WebTestCase
{
    /**
     * @return array
     */
    public function dumbData()
    {
        return [
            'coucou hibou' => [__DIR__ . '/csv/my-test-file.csv'],
        ];
    }

    /**
     * @dataProvider dumbData
     *
     * @param string $fileName
     */
    public function testDumb(string $fileName)
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/question-stats/csv/upload',
            [],
            [new UploadedFile($fileName, 'file.csv')]
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}