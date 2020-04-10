<?php


namespace minipoBundle\Controller;


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
            4 /*limit per page*/
        );

        return $this->render('@minipo/Produit/Produits.html.twig',array('p'=>$prod,'lc'=>$list));

    }


}