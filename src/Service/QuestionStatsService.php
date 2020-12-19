<?php


namespace BloomAtWork\Service;


use BloomAtWork\Model\Question;
use BloomAtWork\Model\Response;
use League\Csv\Reader;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class QuestionStatsService
{
    public function createQuestionResponseFromUploadFile($uploadedFile): Question
    {
        $csv = Reader::createFromPath($uploadedFile->getPathname(), 'r');

        $question = $this->createQuestion($csv);

        $records = $csv->getRecords(['value']);
        foreach ($records as $record) {
            if ($response = $this->createResponse($record['value'])) {
                $question->addResponse($response);
            }
        }

        return $question;
    }

    private function createQuestion($csv): Question
    {
        $csv->setHeaderOffset(0);
        $header = $csv->getHeader();
        $label = str_replace('# ', '', $header[0]);
        return new Question($label);
    }

    /**
     * @param $note
     * @return Response|false
     */
    private function createResponse(string $note)
    {
        if ($this->validateNote($note)) {
            return (new Response($note));
        }
        return false;
    }

    private function validateNote($note): bool
    {
        return filter_var($note, FILTER_VALIDATE_FLOAT) && 0 <= $note && $note <= 10;
    }

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

    public function validateExtension(string $fileName): void
    {
        $array = explode(".", $fileName);
        $extension = end($array);

        if ('csv' !== $extension) {
            throw new BadRequestHttpException('csv file is required');
        }
    }
}
