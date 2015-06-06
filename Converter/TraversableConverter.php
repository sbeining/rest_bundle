<?php

namespace sbeining\RestBundle\Converter;

/**
 * Traversable converter
 * Converts every single object in a Traversable using the converter factory
 *
 * @author Sascha Beining <sbeining@saltation.de>
 */
class TraversableConverter extends Converter
{
    /** @var \Traversable */
    private $traversable;

    /**
     * @see parent
     *
     * @param \Traversable $traversable
     */
    public function __construct(ConverterFactory $converterFactory,
        \Traversable $traversable
    )
    {
        parent::__construct($converterFactory);
        $this->traversable = $traversable;
    }

    /**
     * @see parent
     */
    public function toOutputFormat()
    {
        $result = array();
        foreach ($this->traversable as $object) {
            $result[] = $this->convertOne($object);
        }

        return $result;
    }

    /**
     * @see parent
     */
    public function fromOutputFormat($object)
    {
        throw new RuntimeException('TraversableConverter::fromOutputFormat cannot be used');
    }
}
