<?php


namespace BloomAtWork\Controller;

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
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function readFile(Request $request)
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
            $label = str_replace('# ','', $header[0]);

            foreach ($records as $record) {

            }
        } catch (\Exception $exception) {
            return $this->json(['error' => $exception->getMessage()]);
        }
    }
}
