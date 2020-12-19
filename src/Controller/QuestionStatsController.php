<?php


namespace BloomAtWork\Controller;

use BloomAtWork\Exception\ApiProblemException;
use BloomAtWork\Model\ApiProblem;
use BloomAtWork\Service\QuestionStatsService;
use Exception;
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
     * Reads the file and returns the statistics of the question
     * @Route("/csv/upload", name="question_stats_upload", methods={"POST"})
     */
    public function readFile(Request $request, QuestionStatsService $service): JsonResponse
    {
        try {
            /** @var UploadedFile $uploadedFile */
            if (!$uploadedFile = $request->files->get('file')) {
                throw new BadRequestHttpException('"file" is required');
            }

            if (!$service->validateExtension($uploadedFile->getClientOriginalName())) {
                throw new BadRequestHttpException('csv file is required');
            }

            $csv = Reader::createFromPath($uploadedFile->getPathname(), 'r');

            $question = $service->createQuestionFromCsv($csv);

            $service->addResponsesFromCsv($question, $csv);

            $stats = $service->getStats($question);

            return $this->json($stats);
        } catch (Exception $exception) {
            $this->throwApiProblemValidationException($exception);
        }
    }

    /**
     * Throws API problem validation exception
     * @param Exception $exception
     */
    private function throwApiProblemValidationException(Exception $exception): void
    {
        $apiProblem = new ApiProblem(
            $exception,
            ApiProblem::TYPE_VALIDATION_ERROR
        );
        $apiProblem->set('error', $exception->getMessage());

        throw new ApiProblemException($apiProblem);
    }
}
