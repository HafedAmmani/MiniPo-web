<?php

namespace minipoBundle\Controller;
use FOS\UserBundle\Model\UserInterface;

use Knp\Component\Pager\Paginator;
use minipoBundle\Entity\Notification;
use minipoBundle\Entity\Reclamation;
use minipoBundle\Entity\Reclamationemploye;
use minipoBundle\Form\ReclamationemployeType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ServiceReclamationEmployeController extends Controller
{
    public function indexEmployeAction()
    {
        return $this->render('@minipo/Default/indexEmploye.html.twig');
    }
    public function AjouterReclamationEmployeAction(Request $request)
    {    $id=$this->getUser()->getId();
        $reclamation=new Reclamationemploye();
        $form=$this->createForm(ReclamationemployeType::class,$reclamation);
        $form=$form->handleRequest($request);
        if($form->isSubmitted() and $form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $classCat = $em->getRepository('minipoBundle:User')->find($id);

            $file=$reclamation->getImage();
            if($file != null){
                $filename= md5(uniqid()) . '.' . $file->guessExtension();
                $file->move($this->getParameter('photos_directory'), $filename);
                $reclamation->setImage($filename);}
            $reclamation->setEtatremp("non traiter");
            $reclamation->setId($classCat);
            $em->persist($reclamation);
            $em->flush();
            $this->addFlash('info', 'Reclamation envoyee !');

            return $this->redirectToRoute('minipo_AfficherMesReclamationEmployes');
        }
        return $this->render('@minipo/Reclamation/AjouterReclamationEmploye.html.twig',array('f'=>$form->createView()));
    }
    public function ModifierEtatReclamationEmployeAction(Request $request,$id)
    {
        $em=$this->getDoctrine()->getManager();
        $reclamation=$em->getRepository((Reclamationemploye::class))->find($id);
        if($request->isMethod('POST')){

            if($reclamation->getEtatremp()=="non traiter"){$reclamation->setEtatremp($request->get('etat'));}

            $reclamation->setReponse($request->get('reponse'));

            $em->flush();
            $notification=new Notification();
            if($reclamation->getEtatremp()=="traiter"){
                $notification->setTitle("Reclamation traiter")
                    ->setDescription($reclamation->getObjet())
                    ->setRoute('minipo_showdetailedEmploye')// I suppose you have a show route for your entity
                    ->setParameters(array('id' => $reclamation->getIdremp()));
                $em->persist($notification);
                $em->flush();
                ;}


            $pusher=$this->get('mrad.pusher.notificaitons');
            $pusher->trigger($notification);
            return $this->redirectToRoute('minipo_AfficherToutesReclamationEmploye');
        }
        return $this->render('@minipo/Reclamation/ModifierEtatReclamationEmploye.html.twig',array('reclamation'=>$reclamation));
    }

    public function SupprimerReclamationEmployeAction($etatr)
    {
        $em=$this->getDoctrine()->getManager();
        $reclamation=$em->getRepository((Reclamationemploye::class))->findOneBy(array('etatremp'=>$etatr));
        $em->remove($reclamation);
        $em->flush();
        return $this->redirectToRoute("minipo_AfficherToutesReclamationEmploye");
    }
    public function AfficherMesReclamationEmployeAction()
    {
        $id=$this->getUser()->getId();
        $reclamation = $this->getDoctrine()->getRepository('minipoBundle:Reclamationemploye')->findBy(array('id'=>$id));
        $notification = $this->getDoctrine()->getRepository(Notification::class)->findAll();
        foreach ($notification as $elt){
            $id= $elt->getId();

        }

        return $this->render('@minipo/Reclamation/AffichageMesReclamationEmploye.html.twig',array('reclamationemploye'=>$reclamation,'id'=>$id));

    }
    public function RemoveAction($id)
    {
        echo $id;
        $em=$this->getDoctrine()->getManager();
        $notification=$em->getRepository('minipoBundle:Notification')->findOneBy(array('id'=>$id));
        $em->remove($notification);
        $em->flush();
        return $this->redirectToRoute("minipo_AfficherMesReclamationEmployes");
    }
    public function AfficherToutesReclamationEmployeAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $reclamation = $this->getDoctrine()->getRepository(Reclamationemploye::class)->findAll();
        $search = $this->getDoctrine()->getRepository(Reclamationemploye::class)->search($request);

        $dql="SELECT r From minipoBundle:Reclamationemploye r JOIN minipoBundle:CategorieReclamationEmp c
                                                     WHERE r.idcatrecemp=c.idcatrecemp" ;
        $query=$em->createQuery($dql);

        // $queryBuilder=$em->getRepository('minipoBundle:Reclamationemploye')->createQueryBuilder('r');
        //$queryBuilder->('r.idcatrecemp','c');


        //->select('r')
        //->from('minipoBundle:Reclamationemploye', 'r');
        // ->join('r.idcatrecemp','c')
        // ->addSelect('c');
        //if(!empty($request->query->getAlnum('filter'))){
        //$queryBuilder->where('r.etatremp LIKE :keyword or r.objet LIKE :keyword or r.description LIKE :keyword or r.dateremp LIKE :keyword or r.reponse LIKE :keyword')
        //->setParameter('keyword','%'.$request->query->getAlnum('filter').'%');}

        //$query=$queryBuilder->getQuery();

        /**
         * @var $paginator Paginator
         */

        $paginator=$this->get('knp_paginator');
        dump(get_class($paginator));
        $result=$paginator->paginate(
            $query,
            $request->query->getInt('page',1),10

        );
        $SommeTraité=0;
        $SommeNonTraité=0;
        $sommeReclamation=0;
        $TAutre=0;
        $TPC=0;
        $NAutre=0;
        $NPC=0;


        foreach($reclamation as $elt) {
            $sommeReclamation=$sommeReclamation+1;
            if($elt->getEtatremp()=="traiter") {
                $SommeTraité = $SommeTraité + 1;
                if($elt->getIdcatrecemp()->getNom()=="Probleme de compte"){
                    $TPC=$TPC+1;}
                if( $elt->getIdcatrecemp()->getNom()=="Autre"){
                    $TAutre=$TAutre+1;
                }
            }
            if($elt->getEtatremp()=="non traiter") {
                $SommeNonTraité = $SommeNonTraité + 1;
                // $NPC=$NPC+1;
                if($elt->getIdcatrecemp()->getNom()=="Probleme de compte"){
                    $NPC=$NPC+1;}

                if($elt->getIdcatrecemp()->getNom()=="Autre"){
                    $NAutre=$NAutre+1;}
            }


        }





        return $this->render('@minipo/Reclamation/AffichageReclamationEmploye.html.twig',array('reclamationemploye'=>$result,'total'=>$sommeReclamation,'sommetraité'=>$SommeTraité ,
            'sommenontraité'=>$SommeNonTraité,'filter'=>$search,'TPC'=>$TPC,'TAutre'=>$TAutre,'NPC'=>$NPC,'NAutre'=>$NAutre));
    }


    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $requestString = $request->get('q');

        $entities =  $em->getRepository(Reclamation::class)->findEntitiesByString($requestString);

        if(!$entities) {
            $result['entities']['error'] = "keine Einträge gefunden";
        } else {
            $result['entities'] = $this->getRealEntities($entities);
        }

        return new Response(json_encode($result));
    }

    public function getRealEntities($entities){

        foreach ($entities as $entity){
            $realEntities[$entity->getId()] = $entity->getEtatr();
        }

        return $realEntities;
    }
    public function showdetailedEmployeAction(Request $request,$id)
    {
        $em=$this->getDoctrine()->getManager();
        $reclamation=$em->getRepository((Reclamationemploye::class))->find($id);
        if($request->isMethod('POST')){
            if($reclamation->getEtatremp()=="non traiter"){
                $reclamation->getObjet();
                $reclamation->setdescription($request->get('description'));
                $em->flush();
            }
            return $this->redirectToRoute('minipo_AfficherMesReclamationEmployes');
        }
        return $this->render('@minipo/Reclamation/DetailReclamationEmploye.html.twig',array('reclamation'=>$reclamation));
    }
    public function ajaxListAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        //$searchParam = $request->get('searchParam');
        $entities = $em->getRepository('minipoBundle:Reclamationemploye')->search($request);
        return $this->render('@minipo/Reclamation/AffichageReclamationEmploye.html.twig', array(
            'entities' => $entities,

        ));
    }

}
