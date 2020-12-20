<?php

namespace BloomAtWork\Tests\Service;

use BloomAtWork\Model\Question;
use BloomAtWork\Model\Response;
use BloomAtWork\Service\QuestionStatsService;
use League\Csv\AbstractCsv;
use League\Csv\Reader;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

class QuestionStatsServiceTest extends TestCase
{
    /**
     * @var QuestionStatsService
     */
    private $service;
    /**
     * @var AbstractCsv|Reader
     */
    private $csv;


    final public function setUp(): void
    {
        parent::setUp();

        $this->csv = Reader::createFromPath(__DIR__ . '/../csv/my-test-file.csv', 'r');
        $this->csv->setHeaderOffset(0);
        $this->service = new QuestionStatsService();
    }

    public function testCreateQuestionFromCsv()
    {
        $question = $this->service->createQuestionFromCsv($this->csv);

        $this->assertEquals('Coucou Hibou', $question->getLabel());
    }

    public function testGetStats()
    {
        $question = $this->service->createQuestionFromCsv($this->csv);
        $this->service->addResponsesFromCsv($question, $this->csv);

        $stats = $this->service->getStats($question);

        $this->assertArrayHasKey('question', $stats);
        $this->assertArrayHasKey('label', $stats['question']);
        $this->assertArrayHasKey('statistics', $stats['question']);
        $this->assertArrayHasKey('min', $stats['question']['statistics']);
        $this->assertArrayHasKey('max', $stats['question']['statistics']);
        $this->assertArrayHasKey('mean', $stats['question']['statistics']);

        $this->assertEquals('Coucou Hibou', $stats['question']['label']);
        $this->assertEquals(1, $stats['question']['statistics']['min']);
        $this->assertEquals(2, $stats['question']['statistics']['max']);
        $this->assertEquals(1.5, $stats['question']['statistics']['mean']);
    }

    public function testValidateExtension()
    {
        $isValid = $this->service->validateExtension('my-test-file.csv');
        $this->assertTrue($isValid);

        $isValid = $this->service->validateExtension('my-test-file.txt');
        $this->assertFalse($isValid);
    }

    public function testAddResponsesFromCsv()
    {
        $question = $this->service->createQuestionFromCsv($this->csv);

        $this->service->addResponsesFromCsv($question, $this->csv);

        $questionTest = (new Question('Coucou Hibou'))
            ->addResponse((new Response(1.0)))
            ->addResponse((new Response(2.0)));

        $this->assertEquals($questionTest, $question);
    }

    public function testCreateResponse()
    {
        $question = $this->service->createQuestionFromCsv($this->csv);
        $this->service->addResponsesFromCsv($question, $this->csv);

        $method = new ReflectionMethod(QuestionStatsService::class, 'createResponse');
        $method->setAccessible(true);

        $response = $method->invoke($this->service, 1);
        $this->assertInstanceOf(Response::class, $response);

        $response = $method->invoke($this->service, -1);
        $this->assertFalse($response);
    }

    public function testValidateNote()
    {
        $method = new ReflectionMethod(QuestionStatsService::class, 'validateNote');
        $method->setAccessible(true);

        $isValid = $method->invoke($this->service, 1);
        $this->assertTrue($isValid);

        $isValid = $method->invoke($this->service, -1);
        $this->assertFalse($isValid);

        $isValid = $method->invoke($this->service, 11);
        $this->assertFalse($isValid);

        $isValid = $method->invoke($this->service, 'ko');
        $this->assertFalse($isValid);
    }
}
