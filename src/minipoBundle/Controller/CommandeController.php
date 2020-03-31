<?php


namespace minipoBundle\Controller;


use minipoBundle\Entity\Commande;
use minipoBundle\Entity\Facture;
use minipoBundle\Entity\Lignecommande;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CommandeController extends Controller
{

    public function allCommandesAction()
    {
        $commandes=$this->getDoctrine()->getRepository(Commande::class)->findAll();
        return $this->render('@minipo/Commande/Commandes.html.twig',array('cmd'=>$commandes));
    }
    public function accepterCmdAction($id){
        $em=$this->getDoctrine()->getManager();
        $commandes=$em->getRepository(Commande::class)->find($id);

            $commandes->setEtatc("Acceptee");
            $em->flush();
            return $this->redirectToRoute('minipo_allCommandes');

    }
    public function refuserCmdAction($id){
        $em=$this->getDoctrine()->getManager();
        $commandes=$em->getRepository(Commande::class)->find($id);

        $commandes->setEtatc("Refusee");
        $em->flush();
        return $this->redirectToRoute('minipo_allCommandes');

    }

    public function commandesCltAction($id)
    {
        //Panier
        $id=$this->getUser()->getId();
        $repo=$this->getDoctrine()->getManager()->getRepository(Lignecommande::class);
        $Panier=$repo->myFindPanier($id);
        //*********
        $repo=$this->getDoctrine()->getManager()->getRepository(Commande::class);
        $list=$repo->myFindCmdByClt($id);
        return $this->render('@minipo/Commande/CommandesClt.html.twig',array('cmd'=>$list,'lc'=>$Panier));

    }

    public function validerCmdAction($id){

        $repo=$this->getDoctrine()->getManager()->getRepository(Commande::class);
        $commande=$repo->myFindPanierByClt($id);
        $commande->setEtatc("Validee");
        $idCmd=$commande->getIdcmd();
        $em=$this->getDoctrine()->getManager();
        $em->flush();

        return $this->redirectToRoute('minipo_createFacture',array('id'=>$id,'idCmd'=>$idCmd));


    }

    public function suppCmdAction($id,$idCmd){

        $em=$this->getDoctrine()->getManager();
        $commandes=$em->getRepository(Commande::class)->find($idCmd);
        //(string)$idPan=$commandes->getIdcmd();
        $etat=$commandes->getEtatc();

        if (strcmp($etat,"Validee")==0) {

            //Supprimer facture
            $repo = $this->getDoctrine()->getManager()->getRepository(Facture::class);
            $facture = $repo->myFindFactByCmd($idCmd);
            $em->remove($facture);
            $em->flush();
            //Supprimer les Produits Commandees (LC)
            $ligneCommandes=$em->getRepository(Lignecommande::class)->findBy(array('idcmd'=>$idCmd));
            foreach ( $ligneCommandes as $value){
                $lc=$em->getRepository(Lignecommande::class)->find($value->getIdlc());
                $em->remove($lc);
                $em->flush();
            }
            //Supprimer Commande
            $commandes=$em->getRepository(Commande::class)->find($idCmd);
            $em->remove($commandes);
            $em->flush();
            //rederection vers la liste des commandes
            return $this->redirectToRoute('minipo_commandesClt',array('id'=>$id));
        }
        else{

            return $this->redirectToRoute('minipo_commandesClt',array('id'=>$id));
        }

    }

    public function detailCmdAction($idCmd){


        $em=$this->getDoctrine()->getManager();
        $commandes=$em->getRepository(Commande::class)->find($idCmd);
        $em->remove($commandes);
        $em->flush();

    }

}