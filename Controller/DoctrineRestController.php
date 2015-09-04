<?php

namespace sbeining\RestBundle\Controller;

/**
 * Doctrine Version of the REST Controller
 *
 * @uses RestController
 */
abstract class DoctrineRestController extends RestController
{
    /**
     * @see parent
     */
    protected function getObject($id)
    {
        $object = $this->getRepository()->find($id);

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
        $em = $this->getEntityManager();
        $em->persist($object);
        $em->flush();
    }

    /**
     * @see parent
     */
    protected function deleteObject($object)
    {
        $em = $this->getEntityManager();
        $em->delete($object);
        $em->flush();
    }

    /**
     * @see parent
     */
    protected function getListTraversable()
    {
        return $this->getRepository()->findAll();
    }

    /**
     * Returns the repository instance
     *
     * @abstract
     * @return Repository
     */
    abstract protected function getRepository();

    /**
     * Returns the entity manager used to save and delete objects
     *
     * @return \Doctrine\Common\Persistence\ObjectManager
     */
    private function getEntityManager()
    {
        return $this->getDoctrine()->getManager();
    }
}
