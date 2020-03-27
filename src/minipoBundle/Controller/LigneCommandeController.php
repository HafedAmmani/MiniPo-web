<?php


namespace minipoBundle\Controller;


use minipoBundle\Entity\Lignecommande;
use minipoBundle\Entity\Produit;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class LigneCommandeController extends Controller
{

    public function aboutProdAction($id){

        $prod=$this->getDoctrine()->getRepository(Produit::class)->find($id);
        return $this->render('@minipo/LigneCommande/AboutProd.html.twig',array('p'=>$prod));
    }

     /*public function ajouterLCAction(Request $request,$idprod){

        $prod=$this->getDoctrine()->getRepository(Produit::class)->find($idprod);
        $lc=new Lignecommande();


        if($request->isMethod('POST')) {

            $lc->setQte($request->get('qte'));
            $lc->setIdcmd(4);
            $lc->setIdprod($idprod);
            $em = $this->getDoctrine()->getManager();
            $em->persist($lc);
            $em->flush();

            //return new \http\Env\Response();
            return $this->redirectToRoute('minipo_afficherPanier');
        }
        return $this->redirectToRoute('minipo_aboutProd',['id'=>$idprod]);
        //return $this->render('@minipo/LigneCommande/AboutProd.html.twig',array('p'=>$prod));
        //return $this->render('@minipo/LigneCommande/MonPanier.html.twig',array('p'=>$lc));

    } */
    public function ajouterLCAction(Request $request/*,$idprod*/)
    {

        //$prod = $this->getDoctrine()->getRepository(Produit::class)->find($idprod);
        $lc = new Lignecommande();


        if($request->isMethod('POST')) {

            $lc->setQte($request->get('qte'));
            $lc->setIdcmd($request->get('idcmd'));
            $lc->setIdprod($request->get('idprod'));
            $em = $this->getDoctrine()->getManager();
            $em->persist($lc);
            $em->flush();

            //return new \http\Env\Response();
            return $this->redirectToRoute('minipo_afficherPanier');
        }
       // return $this->redirectToRoute('minipo_aboutProd', ['id' => $idprod]);
       // return $this->render('@minipo/LigneCommande/AboutProd.html.twig');
        //return $this->render('@minipo/LigneCommande/MonPanier.html.twig',array('p'=>$lc));
        return $this->render('@minipo/LigneCommande/ajouter.html.twig');
    }
    public function afficherPanierAction($id){

        $repo=$this->getDoctrine()->getManager()->getRepository(Lignecommande::class);
        $list=$repo->myFindPanier($id);
        return $this->render('@minipo/LigneCommande/MonPanier.html.twig',array('lc'=>$list));
    }

    public function affTestAction(){

        return $this->render('@minipo/LigneCommande/Test.html.twig');
    }

    public function deleteLcAction($id,$idLc)
    {
        $em=$this->getDoctrine()->getManager();
        $lc=$em->getRepository(Lignecommande::class)->find($idLc);
        $em->remove($lc);
        $em->flush();
        return $this->redirectToRoute("minipo_afficherPanier",array('id'=>$id));
    }



}