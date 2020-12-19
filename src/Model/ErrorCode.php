<?php


namespace BloomAtWork\Model;


class ErrorCode
{
    public const UNKNOW_ERROR = 'unknown_error';
    public const BAD_REQUEST = 'bad_request';

    private $label;

    public function __construct($exceptionClassName)
    {
        switch ($exceptionClassName) {
            case 'BadRequestHttpException':
                $this->label = self::BAD_REQUEST;
                break;
            default:
                $this->label = self::UNKNOW_ERROR;
        }
    }

    public function __toString(): string
    {
        return $this->label;
    }
}
