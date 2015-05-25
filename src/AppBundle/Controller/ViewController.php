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
use Ob\HighchartsBundle\Highcharts\Highchart;
use Ob\HighchartsBundle\Highcharts\Highstock;
use Zend\Json\Expr;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * View controller.
 *
 * @Route("/")
 */
class ViewController extends Controller
{

   /**
    * Lists all AbsenceDay entities.
    *
    * @Route("/", name="view")
    * @Method("GET")
    * @Template("AppBundle:View:index.html.twig")
    */
    public function indexAction()
    {


        $ser = $this->container->get('app.sensorvalue.handler')->all();

        $data = array();
        foreach ($ser as $s) {
            $data[ $s->getSensorData()->getId() ]['series'][] = array($s->getTimestamp()->format('U')*1000,$s->getValue());
            $data[ $s->getSensorData()->getId() ]['name'] = $s->getSensorData()->getName();
        }
        // Chart
        $series = array();
        foreach ($data as $sensor => $sensordata) {
            $series[] = array("name" => $sensordata['name'],  "data" => $sensordata['series'] );
        }

        $ob = new Highstock();
        $ob->chart->renderTo('linechart');  // The #id of the div where to render the chart
        $ob->legend->enabled(true);
        $ob->title->text('Chart Title');
        $ob->xAxis->title(array('text'  => "Idő"));
        $ob->xAxis->type = 'datetime';
        $ob->xAxis->dateTimeLabelFormats = array("day" => "%Y-%b-%m %H:%i");
        $ob->yAxis->title(array('text'  => "Hőmérséklet"));
        $ob->series($series);

        return array(
            'chart' => $ob
        );
    }


}
