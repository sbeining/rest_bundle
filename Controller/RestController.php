<?php

namespace sbeining\RestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Symfony\Component\HttpKernel\Exception\NotFoundHttpException,
    Symfony\Component\HttpFoundation\Response;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Method,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use sbeining\RestBundle\Converter\ConverterFactory;

/**
 * Abstract REST actions class
 * Offers default REST actions GET (List), GET, POST, PUT and DELETE
 *
 * @uses Controller
 */
abstract class RestController extends Controller
{
    /**
     * @Route("/", defaults={"_format": "json"})
     * @Method("GET")
     * @Template("sbeiningRestBundle:Rest:list.json.twig")
     */
    public function listAction()
    {
        $list = $this->getListTravesable();

        return array(
            'objects' => $this->getConverter($list)->toOutputFormat(),
        );
    }

    /**
     * @Route("/{id}", defaults={"_format": "json"})
     * @Method("GET")
     * @Template("sbeiningRestBundle:Rest:show.json.twig")
     */
    public function showAction($id)
    {
        $object = $this->getObject($id);

        return array(
            'object' => $this->getConverter($object)->toOutputFormat(),
        );
    }

    /**
     * @Route("/")
     * @Method("POST")
     */
    public function createAction()
    {
        $response = new Response();
        $object = $this->createObject();
        $data = json_decode($this->getRequest()->getContent(), true);
        if (!$data) {
            return $response->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $this->getConverter($object)->fromOutputFormat($data);

        $this->preCreateHook($object);
        $this->saveObject($object);
        $this->postCreateHook($object);

        $response->headers->set('Location', $this->buildShowUri($object));
        return $response->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * @Route("/{id}")
     * @Method("PUT")
     */
    public function updateAction($id)
    {
        $response = new Response();
        $object = $this->getObject($id);
        $data = json_decode($this->getRequest()->getContent(), true);
        if (!$data) {
            return $response->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $this->preUpdateHook($object);
        $this->getConverter($object)->fromOutputFormat($data);
        $this->postUpdateHook($object);

        $this->saveObject($object);

        return $response;
    }

    /**
     * @Route("/{id}")
     * @Method("DELETE")
     */
    public function deleteAction($id)
    {
        $response = new Response();
        $object = $this->getObject($id);

        $this->preDeleteHook($object);
        $this>deleteObject($object);
        $this->postDeleteHook($object);

        return $response;
    }

    // Hooks

    /**
     * Called before the object is inserted into the database
     *
     * @param Object $object
     */
    protected function preCreateHook($object) { }

    /**
     * Called after the object is inserted into the database
     *
     * @param Object $object
     */
    protected function postCreateHook($object) { }

    /**
     * Called before the object is updated with the data from the request
     *
     * @param Object $object
     */
    protected function preUpdateHook($object) { }

    /**
     * Called after the object is updated with the data from the request but
     * before the changes are saved into the database
     *
     * @param Object $object
     */
    protected function postUpdateHook($object) { }

    /**
     * Called before the object is deleted
     *
     * @param Object $object
     */
    protected function preDeleteHook($object) { }

    /**
     * Called after the object is deleted
     *
     * @param Object $object
     */
    protected function postDeleteHook($object) { }

    /**
     * Tries to find an entity via the id
     *
     * @throws NotFoundHttpException
     * @param mixed $id
     *
     * @return Object An entity
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
     * Saves the object
     *
     * @param Object $object
     */
    protected function saveObject($object)
    {
        $em = $this->getEntityManager();
        $em->persist($object);
        $em->flush();
    }

    /**
     * Deletes the object
     *
     * @param Object $object
     */
    protected function deleteObject($object)
    {
        $em = $this->getEntityManager();
        $em->delete($object);
        $em->flush();
    }

    /**
     * Returns a list of object
     *
     * @return array
     */
    protected function getListTravesable()
    {
        return $this->getRepository()->findAll();
    }

    /**
     * Returns the repository instance
     *
     * @return Repository
     */
    abstract protected function getRepository();

    /**
     * Creates a new entity
     *
     * @return Object A new entity
     */
    abstract protected function createObject();

    /**
     * Returns the converter factory
     *
     * @return ConverterFactory
     */
    abstract protected function getConverterFactory();

    /**
     * Builds a show uri for the object
     * Used a a location header after a POST request
     *
     * @param Object $object
     *
     * @return string
     */
    abstract protected function buildShowUri($object);

    /**
     * Returns the converter for the object
     *
     * @param Object $object
     *
     * @return Converter
     */
    private function getConverter($object)
    {
        return $this->getConverterFactory()->createConverterFor($object);
    }

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
