<?php


namespace minipoBundle\Controller;


use minipoBundle\Entity\Lignecommande;
use minipoBundle\Entity\Produit;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProduitController extends Controller
{
    public function allProduitsAction()
    {
        $prod=$this->getDoctrine()->getRepository(Produit::class)->findAll();
        $id=$this->getUser()->getId();
        $repo=$this->getDoctrine()->getManager()->getRepository(Lignecommande::class);
        $list=$repo->myFindPanier($id);
        return $this->render('@minipo/Produit/Produits.html.twig',array('p'=>$prod,'lc'=>$list));

    }


}