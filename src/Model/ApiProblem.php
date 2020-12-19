<?php


namespace BloomAtWork\Model;


use Symfony\Component\HttpFoundation\Response;

class ApiProblem
{
    const TYPE_VALIDATION_ERROR = 'validation_error';

    private static $titles = [
        self::TYPE_VALIDATION_ERROR => 'There was a validation error',
    ];
    private $statusCode;
    private $type;
    private $title;
    private $extraData = [];

    public function __construct($exception, $type)
    {
        if (method_exists($exception, 'getCode')) {
            $this->statusCode = $exception->getCode();
        }
        if (method_exists($exception, 'getStatusCode')) {
            $this->statusCode = $exception->getStatusCode();
        }

        $this->statusCode = ($this->statusCode === 0) ? Response::HTTP_INTERNAL_SERVER_ERROR : $this->statusCode;
        $this->type = $type;
        if (!isset(self::$titles[$type])) {
            throw new \InvalidArgumentException('No title for type ' . $type);
        }
        $this->title = self::$titles[$type];
    }

    public function toArray()
    {
        return array_merge(
            $this->extraData,
            array(
                'status' => $this->statusCode,
                'type' => $this->type,
                'title' => $this->title,
            )
        );
    }

    public function set($name, $value)
    {
        $this->extraData[$name] = $value;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function getTitle()
    {
        return $this->title;
    }
}
