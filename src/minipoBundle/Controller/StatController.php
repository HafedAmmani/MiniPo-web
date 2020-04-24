<?php

namespace minipoBundle\Controller;

use Ob\HighchartsBundle\Highcharts\Highchart;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class StatController extends Controller
{
    public function indexclientexterneAction()
    {
        return $this->render('@minipo/Default/index.html.twig');
    }
    public function StatRecCltAction()
    {
        // Chart
        $ob = new Highchart();
        $ob2=new Highchart();
        $ob3 = new Highchart();
        $ob4=new Highchart();
        $ob->chart->renderTo('linechart');  // The #id of the div where to render the chart
        $ob->title->text('Reclamation Client non traiter par categorie ');
        $ob->plotOptions->pie(array(
            'allowPointSelect'=>true,
            'cursor'=>'pointer',
            'datalabels'=>array('enabled'=>false),
            'showInLegend'=>true
        ));
        $ob2->chart->renderTo('linechart2');  // The #id of the div where to render the chart
        $ob2->title->text('Reclamation Client  traiter par categorie ');
        $ob2->plotOptions->pie(array(
            'allowPointSelect'=>true,
            'cursor'=>'pointer',
            'datalabels'=>array('enabled'=>false),
            'showInLegend'=>true
        ));
        $ob3->chart->renderTo('linechart3');  // The #id of the div where to render the chart
        $ob3->title->text('Reclamation Employe non traiter par categorie ');
        $ob3->plotOptions->pie(array(
            'allowPointSelect'=>true,
            'cursor'=>'pointer',
            'datalabels'=>array('enabled'=>false),
            'showInLegend'=>true
        ));
        $ob4->chart->renderTo('linechart4');  // The #id of the div where to render the chart
        $ob4->title->text('Reclamation Employe  traiter par categorie ');
        $ob4->plotOptions->pie(array(
            'allowPointSelect'=>true,
            'cursor'=>'pointer',
            'datalabels'=>array('enabled'=>false),
            'showInLegend'=>true
        ));
        $em=$this->getDoctrine()->getManager();


        $query=$em->createQuery("SELECT c.nom as categorie, count(r.etatr) as nontraiter 
              FROM minipoBundle:Reclamation r JOIN minipoBundle:CategorieReclamation c 
              WHERE r.idcatrec=c.idcatrec and r.etatr='non traiter'GROUP BY c.nom");

        $query2=$em->createQuery("SELECT c.nom as categorie, count(r.etatr) as traiter 
              FROM minipoBundle:Reclamation r JOIN minipoBundle:CategorieReclamation c 
              WHERE r.idcatrec=c.idcatrec and r.etatr='traiter'GROUP BY c.nom");

        $query3=$em->createQuery("SELECT c.nom as categorie, count(r.etatremp) as nontraiter 
              FROM minipoBundle:Reclamationemploye r JOIN minipoBundle:CategorieReclamationEmp c 
              WHERE r.idcatrecemp=c.idcatrecemp and r.etatremp='non traiter'GROUP BY c.nom");

        $query4=$em->createQuery("SELECT c.nom as categorie, count(r.etatremp) as traiter 
              FROM minipoBundle:Reclamationemploye r JOIN minipoBundle:CategorieReclamationEmp c 
              WHERE r.idcatrecemp=c.idcatrecemp and r.etatremp='traiter'GROUP BY c.nom");

        $resultat=$query->getResult();
        $resultat2=$query2->getResult();
        $resultat3=$query3->getResult();
        $resultat4=$query4->getResult();
        $data=array();
        $data2=array();
        $data3=array();
        $data4=array();
        foreach($resultat as $value){
            $a=array($value['categorie'],intval($value['nontraiter']));
            array_push($data,$a);
        }
        foreach($resultat2 as $value){
            $a=array($value['categorie'],intval($value['traiter']));
            array_push($data2,$a);
        }
        foreach($resultat3 as $value){
            $a=array($value['categorie'],intval($value['nontraiter']));
            array_push($data3,$a);
        }
        foreach($resultat4 as $value){
            $a=array($value['categorie'],intval($value['traiter']));
            array_push($data4,$a);
        }

        $ob->series (array(
            array("type" => "pie",    "name" => 'Etat non traiter ','data'=>$data)
        ));
        $ob2->series (array(
            array("type" => "pie",    "name" => 'Etat  traiter ','data'=>$data2)
        ));
        $ob3->series (array(
            array("type" => "pie",    "name" => 'Etat non  traiter ','data'=>$data3)
        ));
        $ob4->series (array(
            array("type" => "pie",    "name" => 'Etat  traiter ','data'=>$data4)
        ));

        return $this->render('@minipo/Reclamation/Stat.html.twig', array(
            'chart' => $ob,
            'chart2' => $ob2,
            'chart3' => $ob3,
            'chart4' => $ob4
        ));
    }

}
