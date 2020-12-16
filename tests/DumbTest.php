<?php

namespace BloomAtWork\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;

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
            ['file' => new UploadedFile($fileName, 'file.csv')]
        );

        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseHeaderSame('Content-Type', 'application/json');

        $content = $client->getResponse()->getContent();
        $this->assertJson($content);

        $content = json_decode($content, true);

        $this->assertArrayHasKey('question', $content);
        $this->assertArrayHasKey('label', $content['question']);
        $this->assertArrayHasKey('statistics', $content['question']);
        $this->assertArrayHasKey('min', $content['question']['statistics']);
        $this->assertArrayHasKey('max', $content['question']['statistics']);
        $this->assertArrayHasKey('mean', $content['question']['statistics']);

        $this->assertEquals('Coucou Hibou', $content['question']['label']);
        $this->assertEquals(1, $content['question']['statistics']['min']);
        $this->assertEquals(2, $content['question']['statistics']['max']);
        $this->assertEquals(1.5, $content['question']['statistics']['mean']);
    }
}
