<?php


namespace minipoBundle\Controller;


use minipoBundle\Entity\Commande;
use minipoBundle\Entity\Facture;
use minipoBundle\Entity\Lignecommande;
use minipoBundle\Entity\Livraison;
use minipoBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CommandeController extends Controller
{

    public function allCommandesAction(Request $request)
    {
        //************User connecté********************
        $id=$this->getUser()->getId();
        $em=$this->getDoctrine()->getManager();
        $user=$em->getRepository(User::class)->find($id);
        $photo=$user->getImage();

        $repo=$this->getDoctrine()->getManager()->getRepository(Commande::class);
        $lists=$repo->myFindAllCmd();

        $list  = $this->get('knp_paginator')->paginate(
            $lists,
            $request->query->getInt('page', 1), /*page number*/
            20 /*limit per page*/
        );


        return $this->render('@minipo/Commande/Commandes.html.twig',array('cmd'=>$list,'img'=>$photo));
    }


    public function accepterCmdAction($id){
        $em=$this->getDoctrine()->getManager();
        $commandes=$em->getRepository(Commande::class)->find($id);
        $liv=$em->getRepository(Livraison::class)->findBy(array('idc'=>$commandes->getIdcmd()));
        $etat=$commandes->getEtatc();
        if (strcmp($etat,"Validee")==0) {

            $commandes->setEtatc("Acceptee");
            $em->flush();

            $liv[0]->setEtatl("en attente");
            $em->flush();

            return $this->redirectToRoute('minipo_allCommandes');
        }

        return $this->redirectToRoute('minipo_allCommandes');

    }
    public function refuserCmdAction($id){
        $em=$this->getDoctrine()->getManager();
        $commandes=$em->getRepository(Commande::class)->find($id);
        $liv=$em->getRepository(Livraison::class)->findBy(array('idc'=>$commandes->getIdcmd()));

        $etat=$commandes->getEtatc();
        if (strcmp($etat,"Validee")==0) {
            $commandes->setEtatc("Refusee");
            $em->flush();
            $liv[0]->setEtatl("refusee");
            $em->flush();
            return $this->redirectToRoute('minipo_allCommandes');
        }

        return $this->redirectToRoute('minipo_allCommandes');

    }

    public function commandesCltAction(Request $request,$id)
    {
        //Panier
        $id=$this->getUser()->getId();
        $repo=$this->getDoctrine()->getManager()->getRepository(Lignecommande::class);
        $Panier=$repo->myFindPanier($id);

        //*********
        $id=$this->getUser()->getId();
        $repo=$this->getDoctrine()->getManager()->getRepository(Commande::class);
        $lists=$repo->myFindCmdByClt($id);

        $list  = $this->get('knp_paginator')->paginate(
            $lists,
            $request->query->getInt('page', 1), /*page number*/
            20 /*limit per page*/
        );



        return $this->render('@minipo/Commande/CommandesClt.html.twig',array('cmd'=>$list,'lc'=>$Panier));

    }


    public function validerCmdAction(Request $request,$id){

        //Panier
        //$id=$this->getUser()->getId();
        $repo=$this->getDoctrine()->getManager()->getRepository(Lignecommande::class);
        $Panier=$repo->myFindPanier($id);
        //*********

        $em = $this->getDoctrine()->getManager();
        $repo=$this->getDoctrine()->getManager()->getRepository(Commande::class);
        $commande=$repo->myFindPanierByClt($id);

        //***********************************
        $repo=$this->getDoctrine()->getManager()->getRepository(Lignecommande::class);
        $lc=$repo->myFindLcByCmd($commande->getIdcmd());
        //***********************************
        if($request->isMethod('post')) {

            $commande->setEtatc("Validee");
            $commande->setDatec(new \DateTime("now"));
            $idCmd = $commande->getIdcmd();
            $em->flush();

            $facture=new Facture();
            $facture->setIdcmd($commande);
            $facture->setDatef(new \DateTime("now"));
            $facture->setEtatf("Non Payee");
            $em=$this->getDoctrine()->getManager();
            $em->persist($facture);
            $em->flush();

            $liv = new Livraison();
            $dd=new \DateTime("now");
            $liv->setDateliv($dd->format('d/m/y'));
            $liv->setIdc($commande);
            $liv->setEtatl("non livree");
            $liv->setMatriculel("X". rand());
            $liv->setDestination($request->get('des'));
            $em->persist($liv);
            $em->flush();
            return $this->redirectToRoute('minipo_detailCmd',
                array('id'=>$this->getUser()->getId(),'idCmd'=>$commande->getIdcmd()));
        }

        //return $this->redirectToRoute('minipo_checkout',array('id'=>$id,'idCmd'=>$idCmd));
        return $this->render('@minipo/Commande/MyCheckout.html.twig',array('lcc'=>$lc,'lc'=>$Panier));


    }


    public function detailCmdAction($idCmd){

        //Panier
        $id=$this->getUser()->getId();
        $repo=$this->getDoctrine()->getManager()->getRepository(Lignecommande::class);
        $Panier=$repo->myFindPanier($id);
        //*********
        $repo = $this->getDoctrine()->getManager()->getRepository(Lignecommande::class);
        $lc = $repo->myFindLcByCmd($idCmd);
        return $this->render('@minipo/Commande/AboutCommande.html.twig',array('cmd'=>$lc,'lc'=>$Panier));


    }

    public function suppCmdAction($id,$idCmd){

        $em=$this->getDoctrine()->getManager();
        $commandes=$em->getRepository(Commande::class)->find($idCmd);
        $etat=$commandes->getEtatc();

        if (strcmp($etat,"Validee")==0 || strcmp($etat,"Refusee")==0) {

            //Supprimer facture
            $repo = $this->getDoctrine()->getManager()->getRepository(Facture::class);
            $facture = $repo->myFindFactByCmd($idCmd);
            if (!is_null($facture)){
                $em->remove($facture);
                $em->flush();
            }
            //Supprimer les Produits Commandees (LC)
            $ligneCommandes=$em->getRepository(Lignecommande::class)->findBy(array('idcmd'=>$idCmd));
            foreach ( $ligneCommandes as $value){
                $lc=$em->getRepository(Lignecommande::class)->find($value->getIdlc());
                $em->remove($lc);
                $em->flush();
            }
            //Supprimer livraison
            $liv=$em->getRepository(Livraison::class)->findBy(array('idc'=>$idCmd));
            $em->remove($liv[0]);
            $em->flush();
            //Supprimer Commande
            $em->remove($commandes);
            $em->flush();
            //rederection vers la liste des commandes

            return $this->redirectToRoute('minipo_commandesClt',array('id'=>$id));
        }
        else{

            return $this->redirectToRoute('minipo_commandesClt',array('id'=>$id));
        }

    }

    public function rechercheAction(Request $request)
    {
        if($request->isMethod('POST')) {
            $repo = $this->getDoctrine()->getManager()->getRepository(Commande::class);
            $cmd = $repo->myFindCmdByLiv($request->get('idl'));

            return $this->render('@minipo/Commande/RechercheCmd.html.twig', array('cmd' => $cmd));
        }


        return $this->redirectToRoute('minipo_allCommandes');
        echo_block('italic','alert','recherche echouer!!!!');


    }

}