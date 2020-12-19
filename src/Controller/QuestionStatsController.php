<?php


namespace BloomAtWork\Controller;

use BloomAtWork\Exception\JsonResponseException;
use BloomAtWork\Service\QuestionStatsService;
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
    public function readFile(Request $request, QuestionStatsService $service): JsonResponse
    {
        try {
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $request->files->get('file');
            if (!$uploadedFile) {
                throw new BadRequestHttpException('"file" is required');
            }

            $service->validateExtension($uploadedFile->getClientOriginalName());

            $question = $service->createQuestionResponseFromUploadFile($uploadedFile);

            $response = $service->getStats($question);

            return $this->json($response);
        } catch (\Exception $exception) {
            return (new JsonResponseException($exception))->getResponse();
        }
    }
}
