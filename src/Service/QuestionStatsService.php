<?php


namespace BloomAtWork\Service;


use BloomAtWork\Model\Question;
use BloomAtWork\Model\Response;
use League\Csv\Exception;
use League\Csv\Reader;

class QuestionStatsService
{
    /**
     * Creates the question entity from the csv reader
     * @throws Exception
     */
    public function createQuestionFromCsv(Reader $csv): Question
    {
        $csv->setHeaderOffset(0);
        $header = $csv->getHeader();
        $label = str_replace('# ', '', $header[0]);
        return new Question($label);
    }

    /**
     * Add the answers for the question from the csv reader
     */
    public function addResponsesFromCsv(Question $question, Reader $csv): void
    {
        $records = $csv->getRecords(['value']);
        foreach ($records as $record) {
            if ($response = $this->createResponse($record['value'])) {
                $question->addResponse($response);
            }
        }
    }

    /**
     * Creates a response entity if it is validate
     * @return Response|false
     */
    private function createResponse(string $note)
    {
        return $this->validateNote($note) ? (new Response($note)) : false;
    }

    /**
     * Validates if the note is a float and it is between 1 and 10
     */
    private function validateNote(string $note): bool
    {
        return filter_var($note, FILTER_VALIDATE_FLOAT) && 0 <= $note && $note <= 10;
    }

    /**
     * Returns statitics for the question
     */
    public function getStats(Question $question): array
    {
        return [
            'question' => [
                'label' => $question->getLabel(),
                'statistics' => [
                    'min' => $question->getMin(),
                    'max' => $question->getMax(),
                    'mean' => $question->getMean(),
                ],
            ],
        ];
    }

    /**
     * Validates extension if the file name is csv
     */
    public function validateExtension(string $fileName): bool
    {
        return 'csv' === strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    }
}
