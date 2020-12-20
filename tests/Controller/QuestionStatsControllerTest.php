<?php

namespace BloomAtWork\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class QuestionStatsControllerTest extends WebTestCase
{
    /**
     * @return array
     */
    public function readFileData()
    {
        return [
            'coucou hibou' => [__DIR__ . '/../csv/my-test-file.csv'],
        ];
    }

    /**
     * @dataProvider readFileData
     *
     * @param string $fileName
     */
    public function testReadFile(string $fileName)
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/question-stats/csv/upload',
            [],
            ['file' => new UploadedFile($fileName, 'file.csv')]
        );

        $this->assertResponseIsSuccessful();
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

    /**
     * @dataProvider readFileData
     *
     * @param string $fileName
     */
    public function testReadFileTxt(string $fileName)
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/question-stats/csv/upload',
            [],
            ['file' => new UploadedFile($fileName, 'file.txt')]
        );

        $this->assertResponseStatusCodeSame(400);
        $this->assertResponseHeaderSame('Content-Type', 'application/problem+json');

        $content = $client->getResponse()->getContent();
        $this->assertJson($content);

        $content = json_decode($content, true);

        $this->assertArrayHasKey('error', $content);
        $this->assertArrayHasKey('status', $content);
        $this->assertArrayHasKey('type', $content);
        $this->assertArrayHasKey('title', $content);

        $this->assertEquals('csv file is required', $content['error']);
        $this->assertEquals(400, $content['status']);
        $this->assertEquals('validation_error', $content['type']);
        $this->assertEquals('There was a validation error', $content['title']);
    }

    public function testReadFileWithoutFile()
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/question-stats/csv/upload',
        );

        $this->assertResponseStatusCodeSame(400);
        $this->assertResponseHeaderSame('Content-Type', 'application/problem+json');

        $content = $client->getResponse()->getContent();
        $this->assertJson($content);

        $content = json_decode($content, true);

        $this->assertArrayHasKey('error', $content);
        $this->assertArrayHasKey('status', $content);
        $this->assertArrayHasKey('type', $content);
        $this->assertArrayHasKey('title', $content);

        $this->assertEquals('"file" is required', $content['error']);
        $this->assertEquals(400, $content['status']);
        $this->assertEquals('validation_error', $content['type']);
        $this->assertEquals('There was a validation error', $content['title']);
    }
}
