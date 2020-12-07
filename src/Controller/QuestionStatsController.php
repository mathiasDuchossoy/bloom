<?php


namespace BloomAtWork\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * Class QuestionStatsController
 * @Route("/question-stats")
 * @package BloomAtWork\Controller
 */
class QuestionStatsController extends AbstractController
{
    /**
     * @Route("/csv/upload", name="question_stats_upload", methods={"POST"})
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function readFile(Request $request)
    {
        return new JsonResponse('Coucou');
    }
}