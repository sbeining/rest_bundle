<?php

namespace sbeining\RestBundle\Converter;

/**
 * Abstract converter
 * Has to be implemented for each class that should be converted
 *
 * @author Björn Steinbrink <bsteinbrink@saltation.de>
 */
abstract class Converter
{
  /** @var ConverterFactory */
  private $converterFactory;

  /**
   * Constructor
   *
   * @param ConverterFactory $converterFactory
   */
  public function __construct(ConverterFactory $converterFactory)
  {
    $this->converterFactory = $converterFactory;
  }

  /**
   * Converts the object to the desired output format
   *
   * @abstract
   * @return mixed data in the "output format"
   */
  abstract public function toOutputFormat();

  /**
   * Converts from the output format and sets the values in the object
   *
   * @abstract
   * @param mixed $input Input data in the "output format"
   */
  abstract public function fromOutputFormat($input);

  /**
   * Converts a single object using the converter factory
   * Used for child objects
   *
   * @param Object $object
   *
   * @return mixed data in the "output format"
   */
  protected function convertOne($object)
  {
    return $this->converterFactory->createConverterFor($object)->toOutputFormat();
  }

  /**
   * Sets the data for a single object using the converter factory
   * Used for child objects
   *
   * @param Object $object
   *
   * @param mixed $input Input data in the "output format"
   */
  protected function setOne($object, $input)
  {
    $this->converterFactory->createConverterFor($object)->fromOutputFormat($input);
  }
}
