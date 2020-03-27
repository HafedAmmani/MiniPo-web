<?php


namespace minipoBundle\Controller;


use minipoBundle\Entity\Produit;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProduitController extends Controller
{
    public function allProduitsAction()
    {
        $prod=$this->getDoctrine()->getRepository(Produit::class)->findAll();
        return $this->render('@minipo/Produit/Produits.html.twig',array('p'=>$prod));

    }


}