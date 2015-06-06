<?php

namespace sbeining\RestBundle\Converter;

/**
 * Array converter
 * Converts every single object in an array using the converter factory
 *
 * @author Björn Steinbrink <bsteinbrink@saltation.de>
 */
class ArrayConverter extends Converter
{
    /** @var array */
    private $array;

    /**
     * @see parent
     *
     * @param array $array
     */
    public function __construct(
        ConverterFactory $converterFactory,
        array $array
    )
    {
        parent::__construct($converterFactory);
        $this->array = $array;
    }

    /**
     * @see parent
     */
    public function toOutputFormat()
    {
        return array_map(array($this, 'convertOne'), $this->array);
    }

    /**
     * @see parent
     */
    public function fromOutputFormat($object)
    {
        throw new RuntimeException('ArrayConverter::fromOutputFormat cannot be used');
    }
}
