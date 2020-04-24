<?php


namespace minipoBundle\Controller;


use CMEN\GoogleChartsBundle\GoogleCharts\Charts\BarChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\ColumnChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\Diff\DiffColumnChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use minipoBundle\Entity\Affectation;
use minipoBundle\Entity\Conge;
use minipoBundle\Entity\Equipe;
use minipoBundle\Entity\Somme;
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
        $pieChart->getOptions()->setTitle('Pourcentages des nombres de jour pour les employe');
        $pieChart->getOptions()->setHeight(500);
        $pieChart->getOptions()->setWidth(700);
        $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
        $pieChart->getOptions()->getTitleTextStyle()->setColor('#009900');
        $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
        $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);

        /*$repository=$this->getDoctrine()->getManager()->getRepository(User::class);
        $listEmploye = $repository->findemploye();
        $mois="jan";
        $somme = $this->getDoctrine()
            ->getRepository(Somme::class)->findBy(array('mois'=>$mois));
        $temp=0;
        $val=0;
        $now = new \DateTime();
        foreach($listEmploye as $pubspon){
            $date= $pubspon->getDate();
            if($date->format('m.Y') === $now->format('m.Y')){
                $val=$temp+$pubspon->getSalaire();
                $temp=$val;
            }
        }
        echo ($val);*/
        $oldColumnChart = new ColumnChart();
        $repository=$this->getDoctrine()->getManager()->getRepository(User::class);
        $listEmploye = $repository->findemploye();
        $data= array();
        $stat=['Nom employe', 'Salaire'];
        array_push($data,$stat);
        foreach($listEmploye as $listemp) {

            $stat=array();

            $nb=((int)$listemp->getSalaire());
            $stat=[$listemp->getUsername(),$nb];
            array_push($data,$stat);
        }

        $oldColumnChart->getData()->setArrayToDataTable(
            $data
        );
        $oldColumnChart->getOptions()->getLegend()->setPosition('top');
        $oldColumnChart->getOptions()->setWidth(450);
        $oldColumnChart->getOptions()->setHeight(250);

        $newColumnChart = new ColumnChart();
        $newColumnChart->getData()->setArrayToDataTable(
            [
                ['Name', 'Popularity'],
                ['Cesar', 370],
                ['Rachel', 600],
                ['Patrick', 700],
                ['Eric', 1500]
            ]
        );
        $newColumnChart->setOptions($oldColumnChart->getOptions()->setTitle("Salaire des employes")
            ->setWidth(350));

        $diffColumnChart = new DiffColumnChart($oldColumnChart, $newColumnChart);
        $diffColumnChart->getOptions()->getLegend()->setPosition('top');
        $diffColumnChart->getOptions()->setWidth(450);
        $diffColumnChart->getOptions()->setHeight(250);
        $diffColumnChart->getOptions()->getDiff()->getNewData()->setWidthFactor(0.1);



        $bar = new ColumnChart();
        $listEquipe = $this->getDoctrine()
            ->getRepository(Equipe::class)->findAll();
        $listAffectation = $this->getDoctrine()
            ->getRepository(Affectation::class)->findAll();
        $data= array();
        $stat=['Nom equipe', 'Nombre'];
        array_push($data,$stat);
        foreach($listEquipe as $listemp) {

            $stat=array();
            $nom= $listemp->getNomeq();
            $nb=((int)$listemp->getNombre());
            foreach ($listAffectation as $listaff){
                if ($listaff->getNomeq() == $nom){
                    $nb=$nb-1;
                }
            }
            $stat=[$listemp->getNomeq(),$nb];
            array_push($data,$stat);
        }

        $bar->getData()->setArrayToDataTable(
            $data
        );
        $bar->getOptions()->getLegend()->setPosition('top');
        $bar->getOptions()->setWidth(450);
        $bar->getOptions()->setHeight(250);

        $newColumnChart = new ColumnChart();
        $newColumnChart->getData()->setArrayToDataTable(
            [
                ['Name', 'Popularity'],
                ['Cesar', 370],
                ['Rachel', 600],
                ['Patrick', 700],
                ['Eric', 1500]
            ]
        );
        $newColumnChart->setOptions($bar->getOptions()->setTitle("Place vide dans les equipes")
            ->setWidth(350));

        $diffColumnChart = new DiffColumnChart($bar, $newColumnChart);
        $diffColumnChart->getOptions()->getLegend()->setPosition('top');
        $diffColumnChart->getOptions()->setWidth(450);
        $diffColumnChart->getOptions()->setHeight(250);
        $diffColumnChart->getOptions()->getDiff()->getNewData()->setWidthFactor(0.1);








        return ($this->render('@minipo/RH/acceuil.html.twig',array("SommeSalaire"=>$SommeSalaire,
            "SommeEmploye"=>$SommeEmploye,
            "SommeEmployeAffec"=>$SommeEmployeAffec,
            "listconge"=>$listConge,
            'piechart' => $pieChart,
            "testing"=>$data,
            'oldColumnChart' => $oldColumnChart,
            'newColumnChart' => $newColumnChart,
            'diffColumnChart' => $diffColumnChart,
            'bar'=>$bar,
        )));
    }

}