<?php


namespace BloomAtWork\Model;


class Question extends AbstractQuestion
{
    private array $responses = [];

    public function addResponse(Response $response): self
    {
        $this->responses[] = $response;
        return $this;
    }

    public function getMin(): float
    {
        $min = $this->responses[0]->getNote();
        foreach ($this->responses as $response) {
            $min = $min > $response->getNote() ? $response->getNote() : $min;
        }
        return $min;
    }

    public function getMax(): float
    {
        $max = $this->responses[0]->getNote();
        foreach ($this->responses as $response) {
            $max = $max < $response->getNote() ? $response->getNote() : $max;
        }
        return $max;
    }

    public function getMean(): float
    {
        $notes = [];
        foreach ($this->responses as $response) {
            $notes[] = $response->getNote();
        }

        $notes = array_filter($notes);

        $average = array_sum($notes) / count($notes);
        return round($average, 2);
    }
}
