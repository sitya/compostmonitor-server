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
    * @Route("/", name="view")
    * @Method("GET")
    * @Template("AppBundle:View:index.html.twig")
    */
    public function indexAction()
    {


        $ser = $this->container->get('app.sensorvalue.handler')->all();

        $data = array();
        foreach ($ser as $s) {
            $data[ $s->getSensorData()->getType() ][ $s->getSensorData()->getId() ]['series'][] = array($s->getTimestamp()->format('U')*1000,$s->getValue());
            if( empty($data[ $s->getSensorData()->getType() ][ $s->getSensorData()->getId() ]['name']) ) $data[ $s->getSensorData()->getType() ][ $s->getSensorData()->getId() ]['name'] = $s->getSensorData()->getName();
        }
        // Chart
        $series = array();
        foreach ($data as $sensortype => $sensor) {
            foreach ($sensor as $sensorid => $sensordata) {
                $series[] = array("id" => $sensorid, "name" => $sensordata['name'],  "data" => $sensordata['series'], "stack" => $sensortype );
            }
        }

        $ob = new Highstock();

        $ob->global->timezoneOffset( -1*60 );

        $ob->chart->renderTo('linechart');  // The #id of the div where to render the chart
        $ob->chart->zoomType('x');

        $ob->legend->enabled(true);

        $ob->title->text('Hőmérsékleti adatok');

        $ob->xAxis->title(array('text'  => "Idő"));
        $ob->xAxis->dateTimeLabelFormats = array("day" => "%Y-%b-%m %H:%i");
        $ob->xAxis->minRange(24 * 3600 * 1000);

        $ob->yAxis->title(array('text'  => "Hőmérséklet"));

        $ob->tooltip->pointFormat('<span>{series.name}</span>: <b>{point.y:.1f} °C</b><br/>');

        $ob->series($series);

        return array(
            'chart' => $ob
        );
    }

    public function labelName( $in ){
        switch ( $in ) {
            case 'c':
                return "Komposztkazán hőmérsékleti adatai";
                break;
            case 'i':
                return "Lakótér hőmérsékleti adatai";
                break;
            case 'o':
                return "Kültéri hőmérsékleti adatok";
                break;
            default:
                return "Egyéb";
                break;
        }
    }

}
