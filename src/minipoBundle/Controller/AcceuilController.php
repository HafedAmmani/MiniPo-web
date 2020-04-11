<?php


namespace minipoBundle\Controller;


use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use minipoBundle\Entity\Affectation;
use minipoBundle\Entity\Conge;
use minipoBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AcceuilController extends Controller
{
    public function AcceuilAction(){
        $repository=$this->getDoctrine()->getManager()->getRepository(User::class);
        $listEmploye = $repository->findemploye();
        $listAffectation = $this->getDoctrine()
            ->getRepository(Affectation::class)->findAll();
        $SommeSalaire=0;
        $SommeEmploye=0;
        foreach($listEmploye as $elt) {
            $SommeSalaire=$SommeSalaire + $elt->getSalaire();
            $SommeEmploye++;
        }
        $SommeEmployeAffec=0;
        foreach($listAffectation as $elt) {
            $SommeEmployeAffec++;
        }
        $repository=$this->getDoctrine()->getManager()->getRepository(User::class);
        $listConge = $repository->findCongeemploye();


        $pieChart = new PieChart();
        $em= $this->getDoctrine();
        $classes = $em->getRepository(Conge::class)->findAll();

        $data= array();
        $stat=['id', 'nbrjrs'];
        $nb=0;
        array_push($data,$stat);

        foreach($classes as $classe) {

            $stat=array();
            array_push($stat,$classe->getId()->getUsername(),(($classe->getNbrjrs())));
            $nb=($classe->getNbrjrs());
            $stat=[$classe->getId()->getUsername(),$nb];
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





        return ($this->render('@minipo/RH/acceuil.html.twig',array("SommeSalaire"=>$SommeSalaire,
            "SommeEmploye"=>$SommeEmploye,
            "SommeEmployeAffec"=>$SommeEmployeAffec,
            "listconge"=>$listConge,
            'piechart' => $pieChart,
            "testing"=>$data,
        )));
    }

}