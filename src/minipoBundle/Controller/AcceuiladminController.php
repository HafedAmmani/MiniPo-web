<?php


namespace minipoBundle\Controller;


use CMEN\GoogleChartsBundle\GoogleCharts\Charts\ColumnChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\Diff\DiffColumnChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use minipoBundle\Entity\Commande;
use minipoBundle\Entity\Facture;
use minipoBundle\Entity\Livraison;
use minipoBundle\Entity\Produit;
use minipoBundle\Entity\Reclamation;
use minipoBundle\Entity\Reclamationemploye;
use minipoBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AcceuiladminController extends Controller
{
    public function AcceuiladminAction(Request $request){

            $liv=$this->getDoctrine()->getRepository(Livraison::class)->findAll();
            $sumliv=0;
            foreach ($liv as $l){

                if(strcmp($l->getEtatl(),"livree")==0){

                    $sumliv++;
                }
            }

        $reclamation = $this->getDoctrine()->getRepository(Reclamation::class)->findAll();
        $reclamationemploye=$this->getDoctrine()->getRepository(Reclamationemploye::class)->findAll();
            $Sommerec=0;
            $Sommerecemp=0;
            foreach($reclamation as $elt) {
                $Sommerec=$Sommerec+1;}
            foreach($reclamationemploye as $elt) {
                $Sommerecemp=$Sommerecemp+1;}

            $cmd=$this->getDoctrine()->getRepository(Commande::class)->findAll();
            $sc=0;
            foreach ($cmd as $c){

                if(strcmp($c->getEtatc(),"Validee")==0){

                    $sc++;
                }
            }



            $nbp=0;
            $facts=$this->getDoctrine()->getRepository(Facture::class)->findAll();
            foreach ($facts as $f){
                if(strcmp($f->getEtatf(),"Payee")==0){
                    $nbp++;
                }
            }


            $nbNp=0;
            $facts=$this->getDoctrine()->getRepository(Facture::class)->findAll();
            foreach ($facts as $f){
                if(strcmp($f->getEtatf(),"Non Payee")==0){
                    $nbNp++;
                }
            }

            $pieChart = new PieChart();
            $pieChart->getData()->setArrayToDataTable(
                [['EtatFacture', 'EtatF par Client'],
                    ['Payée',     $nbp],
                    ['Non Payée',      $nbNp]

                ]
            );
            $pieChart->getOptions()->setHeight(330);
            $pieChart->getOptions()->setWidth(340);
            $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
            $pieChart->getOptions()->getTitleTextStyle()->setColor('#009900');
            $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
            $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
            $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);



        $oldColumnChart = new ColumnChart();

        $EnAttente=0;
        $facts=$this->getDoctrine()->getRepository(Commande::class)->findAll();
        foreach ($facts as $f){
            if(strcmp($f->getEtatc(),"Validee")==0){
                $EnAttente++;
            }
        }
        $EnCours=0;
        $facts=$this->getDoctrine()->getRepository(Commande::class)->findAll();
        foreach ($facts as $f){
            if(strcmp($f->getEtatc(),"Acceptee")==0){
                $EnCours++;
            }
        }
        $Livre=0;
        $facts=$this->getDoctrine()->getRepository(Livraison::class)->findAll();
        foreach ($facts as $f){
            if(strcmp($f->getEtatl(),"livree")==0){
                $Livre++;
            }
        }
        $refuse=0;
        $facts=$this->getDoctrine()->getRepository(Commande::class)->findAll();
        foreach ($facts as $f){
            if(strcmp($f->getEtatc(),"Refusee")==0){
                $refuse++;
            }
        }


        $oldColumnChart->getData()->setArrayToDataTable([
            ['Etat', 'Total'],
            ['Attente', $EnAttente],
            ['En cours', $EnCours],
            ['Livree', $Livre],
            ['Refusé', $refuse]
        ]
        );
        $oldColumnChart->getOptions()->getLegend()->setPosition('top');
        $oldColumnChart->getOptions()->setWidth(600);
        $oldColumnChart->getOptions()->setHeight(380);

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
        $newColumnChart->setOptions($oldColumnChart->getOptions()->setTitle("Etat des commandes")
            ->setWidth(350));

        $diffColumnChart = new DiffColumnChart($oldColumnChart, $newColumnChart);
        $diffColumnChart->getOptions()->getLegend()->setPosition('top');
        $diffColumnChart->getOptions()->setWidth(450);
        $diffColumnChart->getOptions()->setHeight(250);
        $diffColumnChart->getOptions()->getDiff()->getNewData()->setWidthFactor(0.1);

        $prodss=$this->getDoctrine()->getRepository(Produit::class)->findAll();
        $prods=$this->getDoctrine()->getRepository(Produit::class)->findAll();
        $prod  = $this->get('knp_paginator')->paginate(
            $prods,
            $request->query->getInt('page', 1), /*page number*/
            5 /*limit per page*/
        );



        return $this->render('@minipo/acceuilAdmin.html.twig',array("sumliv"=>$sumliv,
                "Sommerec"=>$Sommerec,
                "Sommerecemp"=>$Sommerecemp,
                "sc"=>$sc,
                'oldColumnChart' => $oldColumnChart,
                'newColumnChart' => $newColumnChart,
                'diffColumnChart' => $diffColumnChart,
                'piechart' => $pieChart,
                'prod'=>$prod,
                'lastprod'=>$prodss,
            ));
    }

}