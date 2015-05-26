<?php

namespace AppBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormFactoryInterface;
use AppBundle\Entity\SensorData;
use AppBundle\Form\SensorDataType;
use AppBundle\Exception\InvalidFormException;

class SensorDataHandler extends SensorData
{
    private $om;
    private $entityClass;
    private $repository;
    private $formFactory;

    public function __construct(ObjectManager $om, $entityClass, FormFactoryInterface $formFactory)
    {
        $this->om = $om;
        $this->entityClass = $entityClass;
        $this->repository = $this->om->getRepository($this->entityClass);
        $this->formFactory = $formFactory;
    }

    /**
     * Get a Sensor.
     *
     * @param mixed $id
     *
     * @return Sensor
     */
    public function get($id)
    {
        return $this->repository->find($id);
    }

    /**
     * Get a Sensor by localid.
     *
     * @param mixed $id
     *
     * @return Sensor
     */
    public function getByLocalid($id)
    {
        return $this->repository->findOneByLocalid($id);
    }

    /**
     * Get a list of Sensors.
     *
     * @param int $limit  the limit of the result
     * @param int $offset starting from the offset
     *
     * @return array
     */
    public function all($limit = 5, $offset = 0)
    {
        return $this->repository->findBy(array(), array('type' => 'ASC'), $limit, $offset);
    }

    /**
     * Create a new SensorData.
     *
     * @param array $parameters
     *
     * @return SensorData
     */
    public function post(array $parameters)
    {
        $sensorData = $this->createSensorData();

        return $this->processForm($sensorData, $parameters, 'POST');
    }

    /**
     * Edit a SensorData.
     *
     * @param SensorData $sensorData
     * @param array         $parameters
     *
     * @return SensorData
     */
    public function put(SensorData $sensorData, array $parameters)
    {
        return $this->processForm($sensorData, $parameters, 'PUT');
    }

    /**
     * Partially update a SensorData.
     *
     * @param SensorData $sensorData
     * @param array         $parameters
     *
     * @return SensorData
     */
    public function patch(PageInterface $sensorData, array $parameters)
    {
        return $this->processForm($sensorData, $parameters, 'PATCH');
    }

    /**
     * Processes the form.
     *
     * @param SensorData $sensorData
     * @param array         $parameters
     * @param String        $method
     *
     * @return SensorData
     *
     * @throws \AppBundle\Exception\InvalidFormException
     */
    private function processForm(SensorData $sensorData, array $parameters, $method = "PUT")
    {
        $form = $this->formFactory->create(new SensorDataType(), $sensorData, array('method' => $method));
        $form->submit($parameters, 'PATCH' !== $method);
        if ($form->isValid()) {
            $sensorData = $form->getData();
            $this->om->persist($sensorData);
            $this->om->flush($sensorData);

            return $sensorData;
        }

        throw new InvalidFormException('Invalid submitted data', $form);
    }

    private function createSensorData()
    {
        return new $this->entityClass();
    }

}
