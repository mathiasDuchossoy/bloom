<?php


namespace BloomAtWork\Model;


class Response
{
    /**
     * @var float
     */
    private $note;

    public function __construct(float $note)
    {
        $this->note = $note;
    }

    public function getNote(): float
    {
        return $this->note;
    }
}
