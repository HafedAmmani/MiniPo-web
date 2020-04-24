<?php


namespace minipoBundle\Controller;


use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use minipoBundle\Entity\Conge;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BilanController extends Controller
{
    public function indexAction() {
        $pieChart = new PieChart();
        $em= $this->getDoctrine();
        $classes = $em->getRepository(Conge::class)->findAll();

        $data= array();
        $stat=['Type', 'nbrjrs'];
        $nb=0;
        array_push($data,$stat);
        foreach($classes as $classe) {
            $stat=array();
            array_push($stat,$classe->getType(),(($classe->getNbrjrs())));
            $nb=($classe->getNbrjrs()); $stat=[$classe->getType(),$nb];
            array_push($data,$stat);
        }
        $pieChart->getData()->setArrayToDataTable(
            $data
        );
        $pieChart->getOptions()->setTitle('Pourcentages des Catégorie pour les Conge');
        $pieChart->getOptions()->setHeight(500);
        $pieChart->getOptions()->setWidth(900);
        $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
        $pieChart->getOptions()->getTitleTextStyle()->setColor('#009900');
        $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
        $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);
        return $this->render('@minipo/RH/Bilan.html.twig', array('piechart' => $pieChart)); }

}