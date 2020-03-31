<?php


namespace minipoBundle\Controller;


use minipoBundle\Entity\Commande;
use minipoBundle\Entity\Facture;
use minipoBundle\Entity\Lignecommande;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class FactureController extends Controller
{

    public function allFacturesAction()
    {
        $fact=$this->getDoctrine()->getRepository(Facture::class)->findAll();
        return $this->render('@minipo/Facture/Factures.html.twig',array('f'=>$fact));
    }

    public function createFactureAction($id,$idCmd)
    {
        $cmd=$this->getDoctrine()->getRepository(Commande::class)->find($idCmd);
        //$idCde=$cmd->getIdcmd();

        $facture=new Facture();
        $facture->setIdcmd($cmd);
        $facture->setDatef(new \DateTime("now"));
        $facture->setEtatf("Non Payee");
        $em=$this->getDoctrine()->getManager();
        $em->persist($facture);
        $em->flush();

        return $this->redirectToRoute('minipo_commandesClt',array('id'=>$id));

    }

    public function facturesCltAction($id)
    {
        //*******************Panier*****************************
        $id=$this->getUser()->getId();
        $repo=$this->getDoctrine()->getManager()->getRepository(Lignecommande::class);
        $Panier=$repo->myFindPanier($id);
        //****************************************************************

        $repo=$this->getDoctrine()->getManager()->getRepository(Facture::class);
        $list=$repo->myFindFactByClt($id);
        return $this->render('@minipo/Facture/FacturesClt.html.twig',array('fact'=>$list,'lc'=>$Panier));
    }

    public function updateEtatFactAction($id,$idFact){

        //*******************Panier*****************************
        $id=$this->getUser()->getId();
        $repo=$this->getDoctrine()->getManager()->getRepository(Lignecommande::class);
        $Panier=$repo->myFindPanier($id);
        //****************************************************************

        $em=$this->getDoctrine()->getManager();
        $facture=$em->getRepository(Facture::class)->find($idFact);
        $facture->setEtatf("Payee");
        $em->flush();
        return $this->redirectToRoute('minipo_facturesClt',array('id'=>$id));
        //return $this->render('@minipo/Facture/FacturesClt.html.twig',array('id'=>$id,'lc'=>$Panier));


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






}