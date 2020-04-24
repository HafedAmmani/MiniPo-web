<?php


namespace minipoBundle\Controller;


use CMEN\GoogleChartsBundle\Exception\GoogleChartsException;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\Diff\DiffColumnChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\Material\ColumnChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use minipoBundle\Entity\Commande;
use minipoBundle\Entity\Facture;
use minipoBundle\Entity\Lignecommande;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Mukadi\Chart\Utils\RandomColorFactory;
use Mukadi\Chart\Chart;
use Mukadi\ChartJSBundle\Chart\Builder;

class FactureController extends Controller
{

    public function allFacturesAction(Request $request)
    {
        $facts=$this->getDoctrine()->getRepository(Facture::class)->findAll();
        $fact  = $this->get('knp_paginator')->paginate(
            $facts,
            $request->query->getInt('page', 1), /*page number*/
            20 /*limit per page*/
        );

        return $this->render('@minipo/Facture/Factures.html.twig',array('f'=>$fact));
    }

    public function createFactureAction($id,$idCmd)
    {
        $cmd=$this->getDoctrine()->getRepository(Commande::class)->find($idCmd);
        $facture=new Facture();
        $facture->setIdcmd($cmd);
        $facture->setDatef(new \DateTime("now"));
        $facture->setEtatf("Non Payee");
        $em=$this->getDoctrine()->getManager();
        $em->persist($facture);
        $em->flush();

        return $this->redirectToRoute('minipo_commandesClt',array('id'=>$id));

    }

    public function facturesCltAction(Request $request,$id)
    {
        //*******************Panier*****************************
        $id=$this->getUser()->getId();
        $repo=$this->getDoctrine()->getManager()->getRepository(Lignecommande::class);
        $Panier=$repo->myFindPanier($id);
        //****************************************************************

        $repo=$this->getDoctrine()->getManager()->getRepository(Facture::class);
        $facts=$repo->myFindFactByClt($id);

        $list  = $this->get('knp_paginator')->paginate(
            $facts,
            $request->query->getInt('page', 1), /*page number*/
            20 /*limit per page*/
        );

        return $this->render('@minipo/Facture/FacturesClt.html.twig',array('fact'=>$list,'lc'=>$Panier));
    }

    public function updateEtatFactAction($id,$idCmd){

        $em=$this->getDoctrine()->getManager();
        $commande=$em->getRepository(Commande::class)->find($idCmd);
        $etat=$commande->getEtatc();
        if (strcmp($etat,"Acceptee")==0){
            $facture=$em->getRepository(Facture::class)->findBy(array('idcmd'=>$idCmd));
            $facture[0]->setEtatf("Payee");
            $facture[0]->setDatef(new \DateTime("now"));
            $em->flush();
            //Notification
            //******************
            return $this->redirectToRoute('minipo_facturesClt',array('id'=>$id));
        }
        else{
            //Notification
            //******************
            return $this->redirectToRoute('minipo_commandesClt',array('id'=>$id));
        }


    }

    public function deleteFactureAction($id,$idCmd)
    {

        $repo=$this->getDoctrine()->getManager()->getRepository(Facture::class);
        $facture=$repo->myFindFactByCmd($idCmd);
        $em=$this->getDoctrine()->getManager();
        $em->remove($facture);
        $em->flush();

        return $this->redirectToRoute('minipo_commandesClt',array('id'=>$id));

    }


    public function paimentAction(Request $request)
    {

        \Stripe\Stripe::setApiKey('sk_test_I0X4HiH1xXhtLJ1RkEe3B6ZU00Q4eok3i2');
        \Stripe\Charge::create([
            'amount' => 999,
            'currency' => 'eur',
            'description' => 'Example charge',
            'source' => $request->request->get('stripeToken'),
        ]);

        return $this->render('@minipo/Facture/Paiment.html.twig');

    }


    public function statistiqueAction()
    {
        $nbp=$this->nbreFactPayee();
        $nbNp=$this->nbreFactNonPayee();
        $pieChart = new PieChart();
        $pieChart->getData()->setArrayToDataTable(
            [['EtatFacture', 'EtatF par Client'],
                ['Payée',     $nbp],
                ['Non Payée',      $nbNp]

            ]
        );
        $pieChart->getOptions()->setTitle('My Daily Activities');
        $pieChart->getOptions()->setHeight(400);
        $pieChart->getOptions()->setWidth(450);
        $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
        $pieChart->getOptions()->getTitleTextStyle()->setColor('#009900');
        $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
        $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);

        $oldColumnChart = new ColumnChart();
        $oldColumnChart->getData()->setArrayToDataTable(
            [
                ['Name', 'Popularity'],
                ['Cesar', 250],
                ['Rachel', 4200],
                ['Patrick', 2900],
                ['Eric', 8200]
            ]
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
        $newColumnChart->setOptions($oldColumnChart->getOptions());

        try {
            $diffColumnChart = new DiffColumnChart($oldColumnChart, $newColumnChart);
        } catch (GoogleChartsException $e) {
        }
        $diffColumnChart->getOptions()->getLegend()->setPosition('top');
        $diffColumnChart->getOptions()->setWidth(450);
        $diffColumnChart->getOptions()->setHeight(250);
        $diffColumnChart->getOptions()->getDiff()->getNewData()->setWidthFactor(0.1);



        return $this->render('@minipo/Facture/stat.html.twig', array('piechart' => $pieChart,'oldColumnChart' => $oldColumnChart,
            'newColumnChart' => $newColumnChart,
            'diffColumnChart' => $diffColumnChart));
    }

    public function nbreFactPayee(){

        $nb=0;
        $facts=$this->getDoctrine()->getRepository(Facture::class)->findAll();
        foreach ($facts as $f){
            if(strcmp($f->getEtatf(),"Payee")==0){
                $nb++;
            }
        }
        return $nb;
    }

    public function nbreFactNonPayee(){

        $nb=0;
        $facts=$this->getDoctrine()->getRepository(Facture::class)->findAll();
        foreach ($facts as $f){
            if(strcmp($f->getEtatf(),"Non Payee")==0){
                $nb++;
            }
        }
        return $nb;
    }




    /*public function statistiqueAction(){


        $em = $this->getDoctrine()->getManager();
        $builder = new Builder($em);
        $builder->query("select count (f.idfact) as fact from  minipoBundle:Facture f where f.etatf='Payee' ")
            ->addDataset('fact','Fact',[
                "backgroundColor" => RandomColorFactory::getRandomRGBAColors(6)
            ])
            ->labels('fact')
        ;

        $chart = $builder->buildChart('my_chart',Chart::PIE);
        echo($chart);
       return $this->render('@minipo/Facture/stat.html.twig',[
            "chart" => $chart,
        ]);

    }*/




}