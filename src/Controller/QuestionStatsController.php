<?php


namespace BloomAtWork\Controller;

use BloomAtWork\Model\Question;
use BloomAtWork\Model\Response;
use League\Csv\Reader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class QuestionStatsController
 * @Route("/question-stats")
 * @package BloomAtWork\Controller
 */
class QuestionStatsController extends AbstractController
{
    /**
     * @Route("/csv/upload", name="question_stats_upload", methods={"POST"})
     */
    public function readFile(Request $request): JsonResponse
    {
        try {
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $request->files->get('file');
            if (!$uploadedFile) {
                throw new BadRequestHttpException('"file" is required');
            }

            $fileName = $uploadedFile->getClientOriginalName();
            $array = explode(".", $fileName);
            $extension = end($array);

            if ('csv' !== $extension) {
                throw new BadRequestHttpException('csv file is required');
            }

            $csv = Reader::createFromPath($uploadedFile->getPathname(), 'r');
            $csv->setHeaderOffset(0);

            $header = $csv->getHeader();
            $records = $csv->getRecords(['value']);
            $label = str_replace('# ', '', $header[0]);

            $question = new Question($label);

            foreach ($records as $record) {
                $value = $record['value'];
                if (filter_var($value, FILTER_VALIDATE_FLOAT)) {
                    if (0 <= $value && $value <= 10)
                    {
                        $response = new Response($record['value']);
                        $question->addResponse($response);
                    }
                }
            }

            $response = [
                'question' => [
                    'label' => $label,
                    'statistics' => [
                        'min' => $question->getMin(),
                        'max' => $question->getMax(),
                        'mean' => $question->getMean(),
                    ],
                ],
            ];

            return $this->json($response);
        } catch (\Exception $exception) {
            return $this->json(['error' => $exception->getMessage()]);
        }
    }
}
