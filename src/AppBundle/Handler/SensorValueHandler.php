<?php

namespace AppBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormFactoryInterface;
use AppBundle\Entity\SensorValue;
use AppBundle\Form\SensorValueType;
use AppBundle\Exception\InvalidFormException;

class SensorValueHandler extends SensorValue
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
     * Get a list of Sensors.
     *
     * @param int $limit  the limit of the result
     * @param int $offset starting from the offset
     *
     * @return array
     */
    public function all()
    {
        return $this->repository->findBy(array(), null);
    }

    /**
     * Get a list of Sensors.
     *
     * @param int $limit  the limit of the result
     * @param int $offset starting from the offset
     *
     * @return array
     */
    public function latest($id)
    {
        return $this->repository->findOneBy(array('sensorData' => $id), array('timestamp' => 'DESC'));
    }

    /**
     * Get a list of a Sensor
     *
     * @return array
     */
    public function getASensor($id)
    {
        return $this->repository->findBySensorData( $id );
    }

    /**
     * Create a new SensorValue.
     *
     * @param array $parameters
     *
     * @return SensorValue
     */
    public function post(array $parameters)
    {
        $sensorValue = $this->createSensorValue();
        return $this->processForm($sensorValue, $parameters, 'POST');
    }

    /**
     * Edit a SensorValue.
     *
     * @param SensorValue $sensorValue
     * @param array         $parameters
     *
     * @return SensorValue
     */
    public function put(SensorValue $sensorValue, array $parameters)
    {
        return $this->processForm($sensorValue, $parameters, 'PUT');
    }

    /**
     * Partially update a SensorValue.
     *
     * @param SensorValue $sensorValue
     * @param array         $parameters
     *
     * @return SensorValue
     */
    public function patch(PageInterface $sensorValue, array $parameters)
    {
        return $this->processForm($sensorValue, $parameters, 'PATCH');
    }

    /**
     * Processes the form.
     *
     * @param SensorValue $sensorValue
     * @param array         $parameters
     * @param String        $method
     *
     * @return SensorValue
     *
     * @throws \AppBundle\Exception\InvalidFormException
     */
    private function processForm(SensorValue $sensorValue, array $parameters, $method = "PUT")
    {
        $form = $this->formFactory->create(new SensorValueType(), $sensorValue, array('method' => $method));
        $form->submit($parameters, 'PATCH' !== $method);
        if ($form->isValid()) {
            $sensorValue = $form->getData();
            $this->om->persist($sensorValue);
            $this->om->flush($sensorValue);

            return $sensorValue;
        }

        throw new InvalidFormException('Invalid submitted data', $form);
    }

    private function createSensorValue()
    {
        return new $this->entityClass();
    }

}
