<?php

namespace sbeining\RestBundle\Converter;

/**
 * Abstract converter factory
 * Has to be implemented for module that knows the entites an
 * implements the concrete converters
 *
 * @author Björn Steinbrink <bsteinbrink@saltation.de>
 */
abstract class ConverterFactory
{
    /**
     * Factory method
     *
     * @param Object $object
     *
     * @return Converter
     */
    public function createConverterFor($object)
    {
        if (is_array($object)) {
            return new ArrayConverter($this, $object);
        }

        if ($object instanceof \Traversable) {
            return new TraversableConverter($this, $object);
        }

        return $this->getConverterFor($object);
    }

    /**
     * @throws InvalidArgumentException If no converter is known for the object
     *
     * @param Object $object
     *
     * @return Converter
     */
    abstract protected function getConverterFor($object);
}
