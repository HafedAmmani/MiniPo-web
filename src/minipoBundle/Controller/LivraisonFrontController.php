<?php

namespace minipoBundle\Controller;

use minipoBundle\Entity\Commande;
use minipoBundle\Entity\Facture;
use minipoBundle\Entity\Livraison;
use minipoBundle\Form\RechercheDestType;
use minipoBundle\Form\RechercheLType;
use minipoBundle\Form\UpdateEtatType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class LivraisonFrontController extends Controller
{
    public function afficheLivreurAction()
    {
        $repository = $this->getDoctrine()->getManager()->getRepository(Livraison::class);
        $listliv = $repository->findLivreur($this->getUser()->getId());
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
            $etat=$liv->getEtatl();
            if(strcmp($etat,"livree")==0){
                $cmd = $em->getRepository(Commande::class)
                    ->find($liv->getIdc());
                $fact = $em->getRepository(Facture::class)
                    ->findBy(array('idcmd'=>$cmd->getIdcmd()));
                $fact[0]->setEtatf("Payee");
                $em->flush();


            }
            if(strcmp($etat,"refusee")==0){
                $cmd = $em->getRepository(Commande::class)
                    ->find($liv->getIdc());
                $cmd->setEtatc("Refusee");
                $em->flush();


            }

            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('minipo_afficheLivreur');

        }
        return $this->render('@minipo/Livraison/updateEtat.html.twig', array('f' => $form->createView(),'livraison' => $liv));
    }
    public function searchByEtatAction(Request $request){
        $liv = new Livraison();
        $form = $this->createForm(RechercheLType::class,$liv);
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
    public function searchAction(Request $request){
        $params = array();
        $content = $request->getContent();
        if (!empty($content))
        {
            $params = json_decode($content, true);
        }
        $em = $this->getDoctrine()->getManager();
        if($params['etatl'] === "all")
            if (!isset($params['destination']))
                $livs = $em->getRepository(Livraison::class)->findBy(array('id' => $this->getUser()->getId()));
            else
                $livs = $em->getRepository(Livraison::class)->searchLivraison($this->getUser()->getId() , $params["etatl"],$params["destination"]);
        else
            if (!isset($params['destination']))
                $livs = $em->getRepository(Livraison::class)->findBy(array('id' => $this->getUser()->getId(),'etatl' => $params["etatl"]));
            else
                $livs = $em->getRepository(Livraison::class)->searchLivraison($this->getUser()->getId() , $params["etatl"],$params["destination"]);
        return $this->render("@minipo/Livraison/search.html.twig", array('livraison'=>$livs));
    }
    public function searchByDestAction(Request $request){
        $liv = new Livraison();
        $form = $this->createForm(RechercheDestType::class,$liv);
        $form = $form->handleRequest($request);
        if($form->isSubmitted()){
            $livs = $this->getDoctrine()->getRepository(Livraison::class)
                ->findBy(array('destination'=> $liv->getDestination()));
        }
        else {
            $livs = $this->getDoctrine()->getRepository(Livraison::class)->findAll();
        }
        return $this->render("@minipo/Livraison/searchbydest.html.twig", array('form'=>$form->createView(),'livraison'=>$livs));
    }
}
