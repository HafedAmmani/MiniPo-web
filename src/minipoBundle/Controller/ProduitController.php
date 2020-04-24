<?php


namespace minipoBundle\Controller;


use minipoBundle\Entity\Categorie;
use Symfony\Component\HttpFoundation\Request;
use minipoBundle\Entity\Lignecommande;
use minipoBundle\Entity\Produit;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProduitController extends Controller
{
    public function allProduitsAction(Request $request)
    {
        //************Panier***************
        $id=$this->getUser()->getId();
        $repo=$this->getDoctrine()->getManager()->getRepository(Lignecommande::class);
        $list=$repo->myFindPanier($id);
        //***************************

        //************liste des produits*****************

        $prods=$this->getDoctrine()->getRepository(Produit::class)->findAll();

        $prod  = $this->get('knp_paginator')->paginate(
            $prods,
            $request->query->getInt('page', 1), /*page number*/
            9 /*limit per page*/
        );

        return $this->render('@minipo/Produit/Produits.html.twig',array('p'=>$prod,'lc'=>$list));

    }

    public function testProduitsAction(Request $request)
    {
        //************Panier***************
        $id=$this->getUser()->getId();
        $repo=$this->getDoctrine()->getManager()->getRepository(Lignecommande::class);
        $list=$repo->myFindPanier($id);
        //***************************

        //************liste des produits*****************

        $prods=$this->getDoctrine()->getRepository(Produit::class)->findAll();

        $prod  = $this->get('knp_paginator')->paginate(
            $prods,
            $request->query->getInt('page', 1), /*page number*/
            20 /*limit per page*/
        );

        return $this->render('@minipo/Produit/Prods.html.twig',array('p'=>$prod,'lc'=>$list));

    }

    public function ppAction($idp)
    {
        //************Panier***************
        $id=$this->getUser()->getId();
        $repo=$this->getDoctrine()->getManager()->getRepository(Lignecommande::class);
        $list=$repo->myFindPanier($id);
        //***************************

        //************liste des produits*****************

        $prod=$this->getDoctrine()->getRepository(Produit::class)->find($idp);


        return $this->render('@minipo/Produit/pp.html.twig',array('p'=>$prod,'lc'=>$list));

    }

    public function affichageAction()
    {
        //************liste des produits*******************
        $prods = $this->getDoctrine()->getRepository(Produit::class)
            ->findAll();
        //************liste des categories*****************
        $cats=$this->getDoctrine()->getRepository(Categorie::class)->findAll();

        //************liste des produits Par Categorie*****************
        $arrayProd=array();
        foreach ($cats as $c) {

            $prod = $this->getDoctrine()->getRepository(Produit::class)
                ->findBy(array('idcateg'=>$c->getIdcateg()));

            $arrayProd[]=$prod;
        }
        //****************************Liste blogs***************************

        $em = $this->getDoctrine()->getManager();
        $blogs = $em->getRepository('minipoBundle:Articles')->findAll();

        //return $this->render('@minipo/Produit/Home.html.twig',$arrayProd);
        return $this->render('@minipo/Produit/Home.html.twig',array('p'=>$prods,'pc'=>$arrayProd,"blogs" => $blogs));

    }

    public function affichageInterneAction()
    {
        //*******************Panier*****************************
        $id=$this->getUser()->getId();
        $repo=$this->getDoctrine()->getManager()->getRepository(Lignecommande::class);
        $Panier=$repo->myFindPanier($id);
        //****************************************************************

        //************liste des produits*******************
        $prods = $this->getDoctrine()->getRepository(Produit::class)
            ->findAll();
        //************liste des categories*****************
        $cats=$this->getDoctrine()->getRepository(Categorie::class)->findAll();

        //************liste des produits Par Categorie*****************
        $arrayProd=array();
        foreach ($cats as $c) {

            $prod = $this->getDoctrine()->getRepository(Produit::class)
                ->findBy(array('idcateg'=>$c->getIdcateg()));

            $arrayProd[]=$prod;
        }

        //****************************Liste blogs***************************

        $em = $this->getDoctrine()->getManager();
        $blogs = $em->getRepository('minipoBundle:Articles')->findAll();


        //return $this->render('@minipo/Produit/Home.html.twig',$arrayProd);
        return $this->render('@minipo/Produit/HomeInterne.html.twig',array('p'=>$prods,'pc'=>$arrayProd,"blogs" => $blogs,'lc'=>$Panier));

    }






}