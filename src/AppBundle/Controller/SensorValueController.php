<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\SensorData;
use AppBundle\Entity\SensorValue;
use AppBundle\Form\SensorDataType;
use AppBundle\Form\SensorValueType;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Request\ParamFetcherInterface;

class SensorValueController extends FOSRestController
{

    /**
     * Record a sensorValue from the submitted data.
     *
     * @param Request $request the request object
     *
     * @return FormTypeInterface|View
     */
    public function postSensorvalueAction(Request $request)
    {
        try {

        	$parameters = $request->request->all();
        	$sensor = $this->container->get('app.sensordata.handler')->getByLocalid($parameters['localid']);
        	$parameters['sensorData'] = $sensor->getId();

            $latest = $this->container->get('app.sensorvalue.handler')->latest( $sensor->getId() );
            if ( $parameters['timestamp'] - $latest->getTimestamp()->format('U') > 3500){
                $minute = date('i', $parameters['timestamp']);
                if ( $minute > 0 && $minute < 30){
                    $parameters['timestamp'] = $parameters['timestamp'] - $minute*60;
                } elseif ( $minute >= 30) {
                    $parameters['timestamp'] = $parameters['timestamp'] + 60*(60-$minute);
                }

                $parameters['timestamp'] = date('Y-m-d H:i', $parameters['timestamp']);
                unset($parameters['localid']);

                $newSensor = $this->container->get('app.sensorvalue.handler')->post(
                    $parameters
                );
                $routeOptions = array(
                    'id' => $newSensor->getId(),
                    '_format' => $request->get('_format')
                );
                return $this->routeRedirectView('api_1_index', $routeOptions, Codes::HTTP_CREATED);
            } else {
                $routeOptions = array(
                    'id' => 1,
                    '_format' => $request->get('_format')
                );
                return $this->routeRedirectView('api_1_index', $routeOptions, Codes::HTTP_CREATED);
            }

        } catch (InvalidFormException $exception) {
            return $exception->getForm();
        }
    }

}
