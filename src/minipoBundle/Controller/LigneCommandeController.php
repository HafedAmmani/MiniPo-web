<?php


namespace minipoBundle\Controller;


use minipoBundle\Entity\Commande;
use minipoBundle\Entity\Lignecommande;
use minipoBundle\Entity\Produit;
use minipoBundle\Form\LignecommandeType;
use minipoBundle\Form\CommandeType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class LigneCommandeController extends Controller
{

    public function aboutProdAction($id){

        $prod=$this->getDoctrine()->getRepository(Produit::class)->find($id);
        return $this->render('@minipo/LigneCommande/AboutProd.html.twig',array('p'=>$prod));
    }

    public function infoProdAction($idProd){

        $prod=$this->getDoctrine()->getRepository(Produit::class)->find($idProd);
        return $this->render('@minipo/LigneCommande/infoProd.html.twig',array('p'=>$prod));
    }


    public function afficherPanierAction($id){

        $repo=$this->getDoctrine()->getManager()->getRepository(Lignecommande::class);
        $list=$repo->myFindPanier($id);
        return $this->render('@minipo/LigneCommande/MonPanier.html.twig',array('lc'=>$list));
    }

    public function affTestAction(

    ){

        return $this->render('default/PDF.html.twig');
    }

    public function deleteLcAction($id,$idLc)
    {
        $em=$this->getDoctrine()->getManager();
        $lc=$em->getRepository(Lignecommande::class)->find($idLc);
        //$quantite=$lc->getQte();
        $prod=$em->getRepository(Produit::class)->find($lc->getIdprod());
        $prod->setQtestock($prod->getQtestock()+$lc->getQte());
        $em->flush();
        (string)$idCmd=$lc->getIdcmd();
        $cmd=$em->getRepository(Commande::class)->find($idCmd);
        $subtot=$lc->getSubtotal();
        $em->remove($lc);
        $em->flush();
        /*
        * aprés de supp lc il faut modifier  total commande:
        * total commande = total commande - subtotal lc a supprimer*/

        $cmd->setTotal($cmd->getTotal()-$subtot);
        $em->flush();


        //verifier que la panier est vide ou non
        $repo=$this->getDoctrine()->getManager()->getRepository(Lignecommande::class);
        $i=$repo->myFindMaxLcByCmd($idCmd);

        if ($i[1]==0){
            //le Panier est vide alors supprimer Panier
            $em=$this->getDoctrine()->getManager();
            $commandes=$em->getRepository(Commande::class)->find($idCmd);
            $em->remove($commandes);
            $em->flush();
            return $this->redirectToRoute("minipo_allProduits");
            //return $this->render('@minipo/LigneCommande/Test.html.twig');

        }
        else{
            //le Panier != vide ===> redirectTo mon panier

            return $this->redirectToRoute("minipo_afficherPanier",array('id'=>$id));
        }
    }

    public function aboutLcAction($idLc)
    {
        //************Panier***************
        $id=$this->getUser()->getId();
        $repo=$this->getDoctrine()->getManager()->getRepository(Lignecommande::class);
        $list=$repo->myFindPanier($id);
        //***************************

        $em=$this->getDoctrine()->getManager();
        $ligne=$em->getRepository(Lignecommande::class)->find($idLc);
        //return $this->render('@minipo/LigneCommande/UpdateQte.html.twig',array('p'=>$ligne,'f'=> $form->createView()));
        return $this->render('@minipo/LigneCommande/UpdateQte.html.twig',array('p'=>$ligne,'lc'=>$list));
    }


    public function updateQteLcAction(Request $request,$idLc)
    {
        //*******************Panier*****************************
        $id=$this->getUser()->getId();
        $repo=$this->getDoctrine()->getManager()->getRepository(Lignecommande::class);
        $Panier=$repo->myFindPanier($id);
        //****************************************************************



        $em=$this->getDoctrine()->getManager();
        // LC a modifier
        $lc=$em->getRepository(Lignecommande::class)->find($idLc);

        //Produit du Lc à modifier
        $idProd=$lc->getIdprod();
        $prod=$em->getRepository(Produit::class)->find($idProd);
        //Qte en stock
        $qteStock=$prod->getQtestock();
        //qte eff and sub eff
        $qeff=$lc->getQte();
        $sub=$lc->getSubtotal();

        $form=$this->createForm(LignecommandeType::class,$lc);
        $form=$form->handleRequest($request);
        if($form->isSubmitted() and $form->isValid()){
            $qq=$lc->getQte();
            if ($qteStock - $qq > 0 || $qteStock - $qq == 0) {
                //modifier Subtotal and qte
                $lc->setSubtotal($lc->getQte() * $prod->getPrix());
                $em->flush();
                //modifier total commande
                $cmd=$em->getRepository(Commande::class)->find($lc->getIdcmd());
                $tot=$cmd->getTotal();
                $tot-=$sub-$lc->getSubtotal();
                $cmd->setTotal($tot);
                $em->flush();
                //modifier qte en stock
                $qteStock+=$qeff-$qq;
                $prod->setQtestock($qteStock);
                $em->flush();


                return $this->redirectToRoute("minipo_afficherPanier", array('id' => $id));
            }

            $em->flush();

            return $this->redirectToRoute("minipo_afficherPanier", array('id' => $id));

        }

        return $this->render('@minipo/LigneCommande/UpdateQte.html.twig',array('p'=>$lc,'lc'=>$Panier,'f'=>$form->createView()));

    }

    public function ajouterLCAction(Request $request,$idProd)
    {
        //*******************Panier*****************************
        $id=$this->getUser()->getId();
        $repo=$this->getDoctrine()->getManager()->getRepository(Lignecommande::class);
        $Panier=$repo->myFindPanier($id);
        //****************************************************************

        //***********************************AjouterajouterLCAction*********************************


        //****user connectée****
        $id=$this->getUser()->getId();
        $em=$this->getDoctrine()->getManager();
        //****** le produit Commandée ******
        $emp=$this->getDoctrine()->getManager();
        $prod = $this->getDoctrine()->getRepository(Produit::class)->find($idProd);

        $lc = new Lignecommande();

        $form=$this->createForm(LignecommandeType::class,$lc);
        $form=$form->handleRequest($request);
        if($form->isSubmitted() and $form->isValid()){

            //*******Quantité demmandée*********
            $qted=$lc->getQte();
            //*******Quantité En Stock********
            $qtes=$prod->getQtestock();
            //*******Quantité Effectif********

            $repo=$this->getDoctrine()->getManager()->getRepository(Lignecommande::class);
            $qeff=$repo->myFindQte($idProd);
            $qeff=$qeff+$qted;

            //Verifier que la qte en Stock est suffisante
            //if($qtes-$qeff>0 || $qtes-$qeff==0 ){
            if($qtes-$qted>0 || $qtes-$qted==0 ){
                //****qte en Stock est suffisante***
                //Verifier l'existance d'un panier
                $repo=$this->getDoctrine()->getManager()->getRepository(Commande::class);
                $cmd=$repo->myFindPanierByClt($id);
                if (!(is_null($cmd))){
                    //*****Panier existe*****
                    //Verifier l'existance du produit Commandé dans le panier
                    $idPan=$cmd->getIdcmd();
                    $repo=$this->getDoctrine()->getManager()->getRepository(Produit::class);
                    $pduit=$repo->myFindProduitByPanier($idPan,$idProd);
                    if (!(is_null($pduit))){

                        //*****Produit existe*****
                        //modifier qteLc/subtotal/TotalCmd/qtestock
                        $repo=$this->getDoctrine()->getManager()->getRepository(Lignecommande::class);
                        $ligne=$repo->myFindLcByProd($prod->getIdprod()/*$pduit->getIdprod()*/,$idPan);
                        //******update qteLc****
                        $qteLc=$ligne->getQte();
                        $ligne->setQte($qteLc+$qted);
                        //******update Subtotal***
                        $totLc=$ligne->getSubtotal();
                        $ligne->setSubtotal($totLc+ $qted * $prod->getPrix());
                        $em->flush();
                        //******update total Cmd***
                        $repo=$this->getDoctrine()->getManager()->getRepository(Commande::class);
                        $tt=$repo->myFindTotalCmd($idPan);
                        $cmd->setTotal($tt);
                        $em->flush();
                        //
                        //******update qte en Stock***
                        $q=$prod->getQtestock();
                        $prod->setQtestock($q-$qted);
                        $emp->flush();

                        return $this->redirectToRoute("minipo_afficherPanier",array('id'=>$id));
                    }
                    else{
                        //if(is_null($pduit)){
                        //*****Produit n'existe pas*****
                        $lc->setIdcmd($cmd);
                        $lc->setIdprod($prod);
                        $lc->setSubtotal($qted * $prod->getPrix());
                        $em = $this->getDoctrine()->getManager();
                        $em->persist($lc);
                        $em->flush();
                        //******update totale***
                        $repo=$this->getDoctrine()->getManager()->getRepository(Commande::class);
                        (string)$tt=$repo->myFindTotalCmd($idPan);
                        $cmd->setTotal($tt);
                        $em->flush();
                        //******update qte en Stock***
                        $q=$prod->getQtestock();
                        $prod->setQtestock($q-$qted);
                        $emp->flush();

                        return $this->redirectToRoute("minipo_afficherPanier",array('id'=>$id));

                    }
                }
                else{
                    //*****Panier n'existe pas*****
                    //***créer panier****
                    $pan=new Commande();
                    $pan->setId($this->getUser());
                    $pan->setEtatc("Non Validee");
                    $pan->setTotal($prod->getPrix()*$qted);
                    $pan->setRefc("CmdX".rand());
                    $pan->setDatec(new \DateTime("now"));
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($pan);
                    $em->flush();
                    //***Ajouter Lc****
                    $lc->setIdcmd($pan);
                    $lc->setIdprod($prod);
                    $lc->setSubtotal($qted * $prod->getPrix());
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($lc);
                    $em->flush();
                    //******update qte en Stock***
                    $q=$prod->getQtestock();
                    $prod->setQtestock($q-$qted);
                    $emp->flush();

                    return $this->redirectToRoute("minipo_afficherPanier",array('id'=>$id));
                }

            }
        }

        return $this->render('@minipo/LigneCommande/AboutProd.html.twig',array('p'=>$prod,'lc'=>$Panier,'f'=> $form->createView()));
    }

    //***********************************************************************************************************



    public function updateLcAction(Request $request)
    {
        $em=$this->getDoctrine()->getManager();
        // LC a modifier
        $id=$this->getUser()->getId();
        $repo=$this->getDoctrine()->getManager()->getRepository(Lignecommande::class);
        $lc=$repo->myFindPanier($id);

        //Produit du Lc à modifier
        $idProd=$lc->getIdprod();
        $prod=$em->getRepository(Produit::class)->find($idProd);

        //Qte en stock
        $qteStock=$prod->getQtestock();
        //qte demmandée
        $qte=$request->get('q');
        /*if($request->isMethod('POST')) {
            $qte=$request->get('qte');
        }*/
        //qte eff
        $repo=$this->getDoctrine()->getManager()->getRepository(Lignecommande::class);
        $qeff=$repo->myFindQte($idProd);
        $qeff=$qeff+$qte;
        //verifier Qte en Stock sufisante
        if($qteStock-$qeff>0 || $qteStock-$qeff==0 ){
            //qte est sufisante
            $lc->setQte($qte);
            $lc->setSubtotal($qte* $prod->getPrix());
            $em->flush();
            return $this->redirectToRoute("minipo_afficherPanier",array('id'=>$id));
        }

        return $this->redirectToRoute("minipo_afficherPanier",array('id'=>$id));

    }

}
