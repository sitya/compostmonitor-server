<?php

namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * SensorValue
 *
 * @ORM\Table(name="SensorValue")
 * @ORM\Entity
 */
class SensorValue
{
    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var float
     *
     * @ORM\Column(type="float")
     */
    private $value;

    /**
     * @var string
     *
     * @ORM\Column(type="datetime")
     */
    private $timestamp;

    /**
     * @ORM\ManyToOne(targetEntity="SensorData")
     * @ORM\JoinColumn(name="sensordata_id",referencedColumnName="id")
     **/
    private $sensorData;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set value
     *
     * @param float $value
     * @return SensorValue
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return float 
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Sets the value of id.
     *
     * @param integer $id the id
     *
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets the value of sensorData.
     *
     * @return mixed
     */
    public function getSensorData()
    {
        return $this->sensorData;
    }

    /**
     * Sets the value of sensorData.
     *
     * @param mixed $sensorData the sensor data
     *
     * @return self
     */
    public function setSensorData($sensorData)
    {
        $this->sensorData = $sensorData;

        return $this;
    }

    /**
     * Gets the value of timestamp.
     *
     * @return mixed
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * Sets the value of timestamp.
     *
     * @param mixed $timestamp the timestamp
     *
     * @return self
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;

        return $this;
    }
}
