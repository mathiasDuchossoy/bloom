<?php


namespace BloomAtWork\Exception;

use BloomAtWork\Model\ErrorCode;
use Exception;
use ReflectionClass;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class JsonResponseException
 * @package BloomAtWork\Exception
 */
final class JsonResponseException
{
    /**
     * @var Exception
     */
    private $exception;
    /**
     * @var int
     */
    private $codeError;

    /**
     * JsonResponseException constructor.
     * @param Exception $exception
     * @param int $codeError
     */
    public function __construct(Exception $exception, $codeError = Response::HTTP_INTERNAL_SERVER_ERROR)
    {
        $this->exception = $exception;
        $this->codeError = $codeError;

        $this->initialize();
    }

    public function initialize(): void
    {
        if (method_exists($this->exception, 'getCode')) {
            $this->codeError = ($this->codeError === Response::HTTP_INTERNAL_SERVER_ERROR || $this->codeError === 0) ? $this->exception->getCode() : $this->codeError;
        }
        if (method_exists($this->exception, 'getStatusCode')) {
            $this->codeError = ($this->codeError === Response::HTTP_INTERNAL_SERVER_ERROR || $this->codeError === 0) ? $this->exception->getStatusCode() : $this->codeError;
        }

        $this->codeError = ($this->codeError === 0) ? Response::HTTP_INTERNAL_SERVER_ERROR : $this->codeError;
    }

    public function getResponse(): JsonResponse
    {
        $ref = new ReflectionClass($this->exception);
        $shortName = $ref->getShortName();

        $response = [
            'error' =>
                [
                    'message' => $this->exception->getMessage(),
                    'status' => $this->codeError,
                    'code' => (string)(new ErrorCode($shortName)),
                ],
        ];
        return new JsonResponse($response, $this->codeError);
    }
}
