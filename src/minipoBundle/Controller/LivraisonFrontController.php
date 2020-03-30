<?php

namespace minipoBundle\Controller;

use minipoBundle\Entity\Livraison;
use minipoBundle\Form\RechercheType;
use minipoBundle\Form\UpdateEtatType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class LivraisonFrontController extends Controller
{
    public function afficheLivreurAction()
    {
        $repository = $this->getDoctrine()->getManager()->getRepository(Livraison::class);
        $listliv = $repository->findLivreur();
        return ($this->render('@minipo/Livraison/affichLivraisonLivreur.html.twig', array("listeliv" => $listliv)));
    }

    public function updateEtatAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $liv = $em->getRepository(Livraison::class)
            ->find($id);
        $form = $this->createForm(UpdateEtatType::class, $liv);
        $form = $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('minipo_afficheLiv');
        }
        return $this->render('@minipo/Livraison/updateEtat.html.twig', array('f' => $form->createView()));
    }
    public function searchByEtatAction(Request $request){
        $liv = new Livraison();
        $form = $this->createForm(RechercheType::class,$liv);
        $form = $form->handleRequest($request);
        if($form->isSubmitted()){
            $livs = $this->getDoctrine()->getRepository(Livraison::class)
                ->findBy(array('etatl'=> $liv->getEtatl()));
        }
        else {
            $livs = $this->getDoctrine()->getRepository(Livraison::class)->findAll();
        }
        return $this->render("@minipo/Livraison/searchbyetat.html.twig", array('form'=>$form->createView(),'livraison'=>$livs));
    }

}
