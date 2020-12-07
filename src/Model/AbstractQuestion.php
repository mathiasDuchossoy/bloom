<?php

namespace BloomAtWork\Model;

abstract class AbstractQuestion
{
    /**
     * @var string $label
     */
    protected $label;

    public function __construct($label)
    {
        $this->label = $label;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * @return float
     */
    public abstract function getMin(): float;

    /**
     * @return float
     */
    public abstract function getMax(): float;

    /**
     * @return float
     */
    public abstract function getMean(): float;
}