<?php

namespace sbeining\RestBundle\Controller;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Propel Version of the REST Controller
 *
 * @uses RestController
 */
abstract class PropelRestController extends RestController
{
    /**
     * @see parent
     */
    protected function getObject($id)
    {
        $object = $this->getQuery()->findPk($id);

        if (!$object) {
            return new NotFoundHttpException('Object not found');
        }

        return $object;
    }

    /**
     * @see parent
     */
    protected function saveObject($object)
    {
        $object->save();
    }

    /**
     * @see parent
     */
    protected function deleteObject($object)
    {
        $object->delete();
    }

    /**
     * @see parent
     */
    protected function getListTraversable()
    {
        return $this->getQuery()->find();
    }

    /**
     * Returns the query instance
     *
     * @abstract
     * @return ModelCriteria
     */
    abstract protected function getQuery();
}
