<?php


namespace BloomAtWork\Model;


class Question extends AbstractQuestion
{
    private array $responses = [];

    private function addResponse(Response $response)
    {
        $this->responses[] = $response;
    }

    public function getMin(): float
    {
        // TODO: Implement getMin() method.
    }

    public function getMax(): float
    {
        // TODO: Implement getMax() method.
    }

    public function getMean(): float
    {
        // TODO: Implement getMean() method.
    }
}
