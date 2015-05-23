<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SensorData
 *
 * @ORM\Table(name="SensorData")
 * @ORM\Entity
 */
class SensorData
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=16)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="localid", type="string", length=32, unique=true)
     */
    private $localid;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=64)
     */
    private $name;

    /**
     *
     * @ORM\OneToMany(targetEntity="SensorValue", mappedBy="id")
     */
    private $sensorValues;

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
     * Set type
     *
     * @param string $type
     * @return SensorData
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set localid
     *
     * @param string $localid
     * @return SensorData
     */
    public function setLocalid($localid)
    {
        $this->localid = $localid;

        return $this;
    }

    /**
     * Get localid
     *
     * @return string 
     */
    public function getLocalid()
    {
        return $this->localid;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return SensorData
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
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
     * Gets the value of sensorValues.
     *
     * @return mixed
     */
    public function getSensorValues()
    {
        return $this->sensorValues;
    }

    /**
     * Sets the value of sensorValues.
     *
     * @param mixed $sensorValues the sensor values
     *
     * @return self
     */
    public function setSensorValues($sensorValues)
    {
        $this->sensorValues = $sensorValues;

        return $this;
    }
}
