<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\SensorData;
use AppBundle\Form\SensorDataType;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Request\ParamFetcherInterface;

/**
 * SensorData controller.
 *
 * @Route("/")
 */
class SensorDataController extends FOSRestController
{

    /**
     * Lists all SensorData entities.
     *
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing pages.")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="5", description="How many pages to return.")
     *
     * @Annotations\View(
     *  templateVar="pages"
     * )
     *
     * @param Request               $request      the request object
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     *
     * @return array
     */
    public function indexAction()
    {

        return $this->container->get('app.sensordata.handler')->all();
    }
    /**
     * Creates a new SensorData entity.
     *
     * @Route("/", name="sensordata_create")
     * @Method("POST")
     * @Template("AppBundle:SensorData:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new SensorData();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('sensordata_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a SensorData entity.
     *
     * @param SensorData $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(SensorData $entity)
    {
        $form = $this->createForm(new SensorDataType(), $entity, array(
            'action' => $this->generateUrl('sensordata_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Presents the form to use to create a new page.
     *
     * @Annotations\View(
     *  templateVar = "form"
     * )
     *
     * @return FormTypeInterface
     */
    public function newSensorAction()
    {
        return $this->createForm(new SensorDataType());
    }

    /**
     * Create a Page from the submitted data.
     *
     * @Annotations\View(
     *  template = "AppBundle:new.html.twig",
     *  statusCode = Codes::HTTP_BAD_REQUEST,
     *  templateVar = "form"
     * )
     *
     * @param Request $request the request object
     *
     * @return FormTypeInterface|View
     */
    public function postSensorAction(Request $request)
    {
        try {
            $newSensor = $this->container->get('app.sensordata.handler')->post(
                $request->request->all()
            );
            $routeOptions = array(
                'id' => $newSensor->getId(),
                '_format' => $request->get('_format')
            );
            return $this->routeRedirectView('api_1_index', $routeOptions, Codes::HTTP_CREATED);
        } catch (InvalidFormException $exception) {
            return $exception->getForm();
        }
    }

    /**
     * Displays a form to create a new SensorData entity.
     *
     * @Route("/new", name="sensordata_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new SensorData();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a SensorData entity.
     *
     * @Route("/{id}", name="sensordata_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:SensorData')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SensorData entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing SensorData entity.
     *
     * @Route("/{id}/edit", name="sensordata_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:SensorData')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SensorData entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a SensorData entity.
    *
    * @param SensorData $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(SensorData $entity)
    {
        $form = $this->createForm(new SensorDataType(), $entity, array(
            'action' => $this->generateUrl('sensordata_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing SensorData entity.
     *
     * @Route("/{id}", name="sensordata_update")
     * @Method("PUT")
     * @Template("AppBundle:SensorData:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:SensorData')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SensorData entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('sensordata_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a SensorData entity.
     *
     * @Route("/{id}", name="sensordata_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:SensorData')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find SensorData entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('sensordata'));
    }

    /**
     * Creates a form to delete a SensorData entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('sensordata_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
