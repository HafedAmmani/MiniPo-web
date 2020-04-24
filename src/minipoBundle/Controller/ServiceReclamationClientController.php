<?php

namespace minipoBundle\Controller;
use Doctrine\ORM\Mapping\Id;
use http\Env\Response;
use Knp\Component\Pager\Paginator;
use minipoBundle\Entity\Reclamation;
use minipoBundle\Form\ReclamationCommandeType;
use minipoBundle\Form\ReclamationType;
use minipoBundle\Form\SearchReclamationType;
use minipoBundle\minipoBundle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use minipoBundle\Entity\Lignecommande;
class ServiceReclamationClientController extends Controller
{
    public function indexAction()
    {
        return $this->render('@minipo/Reclamation/index.html.twig');
    }
    public function indexClientAction()
    {
        return $this->render('@minipo/Reclamation/indexClient.html.twig');
    }
    public function AjouterReclamationAction(Request $request)
    {
        $id=$this->getUser()->getId();

        //*******************Panier*****************************
        $repo=$this->getDoctrine()->getManager()->getRepository(Lignecommande::class);
        $Panier=$repo->myFindPanier($id);
        //****************************************************************

        $reclamation=new Reclamation();
        $form=$this->createForm(ReclamationType::class,$reclamation);
        $form=$form->handleRequest($request);
        if($form->isSubmitted() and $form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $classCat = $em->getRepository('minipoBundle:User')->find($id);

            $file=$reclamation->getImage();
            if($file !=null ){
                $filename= md5(uniqid()) . '.' . $file->guessExtension();
                $file->move($this->getParameter('photos_directory'), $filename);
                $reclamation->setImage($filename);
            }
            $reclamation->setEtatr("non traiter");
            $reclamation->setId($classCat);
            $em->persist($reclamation);
            $em->flush();

            return $this->redirectToRoute('minipo_AfficherMesReclamation');

        }
        return $this->render('@minipo/Reclamation/AjouterReclamationClient.html.twig',array('lc'=>$Panier,'f'=>$form->createView()));
    }
    public function sendNotification(Request $request)
    {
        $manager = $this->get('mgilet.notification');
        $notif = $manager->createNotification('Hello world !');
        $notif->setMessage('This a notification.');
        $notif->setLink('http://symfony.com/');
        // or the one-line method :
        // $manager->createNotification('Notification subject','Some random text','http://google.fr');

        // you can add a notification to a list of entities
        // the third parameter ``$flush`` allows you to directly flush the entities
        $manager->addNotification(array($this->getUser()), $notif, true);

        return $this->redirectToRoute('homepage');
    }
    public function ModifierEtatReclamationAction(Request $request,$id)
    {
        $em=$this->getDoctrine()->getManager();
        $reclamation=$em->getRepository((Reclamation::class))->find($id);
        if($request->isMethod('POST')){


            if($reclamation->getEtatr()=="non traiter"){$reclamation->setEtatr($request->get('etat'));}
            $reclamation->setReponse($request->get('reponse'));

            $em->flush();
            return $this->redirectToRoute('minipo_AfficherToutesReclamation');
        }
        return $this->render('@minipo/Reclamation/ModifierEtatReclamation.html.twig',array('reclamation'=>$reclamation));
    }
    public function AjouterReclamationCommandeAction(Request $request,$idcmd)
    {   $id=$this->getUser()->getId();
        //*******************Panier*****************************
        $repo=$this->getDoctrine()->getManager()->getRepository(Lignecommande::class);
        $Panier=$repo->myFindPanier($id);
        //****************************************************************


        $c=$this->getDoctrine()->getManager();
        $idcatrec=2;
        $classCatidcmd = $c->getRepository('minipoBundle:Commande')->find($idcmd);

        $reclamation=new Reclamation();
        $form=$this->createForm(ReclamationCommandeType::class,$reclamation);
        $form=$form->handleRequest($request);
        if($form->isSubmitted() and $form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $classCat = $em->getRepository('minipoBundle:User')->find($id);
            $classCatidcmd = $em->getRepository('minipoBundle:Commande')->find($idcmd);
            $classCatidcatrec = $em->getRepository('minipoBundle:CategorieReclamation')->find($idcatrec);
            $file=$reclamation->getImage();
            if($file!=null){
                $filename= md5(uniqid()) . '.' . $file->guessExtension();
                $file->move($this->getParameter('photos_directory'), $filename);
                $reclamation->setImage($filename);}
            $reclamation->setEtatr("non traiter");
            $reclamation->setId($classCat);
            $reclamation->setIdcatrec($classCatidcatrec);
            $reclamation->setIdcmd($classCatidcmd );
            $em->persist($reclamation);
            $em->flush();

            return $this->redirectToRoute('minipo_AfficherMesReclamation');}
        return $this->render('@minipo/Reclamation/AjouterReclamationClientCommande.html.twig',array('c'=>$classCatidcmd,'f'=>$form->createView(),'lc'=>$Panier));
    }

    public function SupprimerReclamationAction($etatr)
    {
        $em=$this->getDoctrine()->getManager();
        $reclamation=$em->getRepository((Reclamation::class))->findOneBy(array('etatr'=>$etatr));
        $em->remove($reclamation);
        $em->flush();
        return $this->redirectToRoute("minipo_AfficherToutesReclamation");
    }
    public function AfficherMesReclamationAction(Request $request)
    {
        $id=$this->getUser()->getId();
        //*******************Panier*****************************
        $repo=$this->getDoctrine()->getManager()->getRepository(Lignecommande::class);
        $Panier=$repo->myFindPanier($id);
        //****************************************************************
        $em = $this->getDoctrine()->getManager();
        $reclamation = $this->getDoctrine()->getRepository('minipoBundle:Reclamation')->findBy(array('id'=>$id));
        $dql="SELECT r From minipoBundle:Reclamation r JOIN minipoBundle:CategorieReclamation c 
                                                      WHERE r.id=:id and  r.idcatrec=c.idcatrec " ;


        $query=$em->createQuery($dql);
        /**
         * @var $paginator Paginator
         */

        $paginator=$this->get('knp_paginator');
        dump(get_class($paginator));
        $result=$paginator->paginate(
            $reclamation,
            $request->query->getInt('page',1),4

        );
        return $this->render('@minipo/Reclamation/AffichageMesReclamation.html.twig',array('reclamation'=>$reclamation,'reclamationclient'=>$result,'lc'=>$Panier));
    }



    public function AfficherToutesReclamationAction(Request $request)
    {   $em = $this->getDoctrine()->getManager();
        $reclamation = $this->getDoctrine()->getRepository(Reclamation::class)->findAll();
        $dql="SELECT r From minipoBundle:Reclamation r JOIN minipoBundle:CategorieReclamation c 
                                                      WHERE r.idcatrec=c.idcatrec" ;

        $query=$em->createQuery($dql);
        /**
         * @var $paginator Paginator
         */

        $paginator=$this->get('knp_paginator');
        //dump(get_class($paginator));
        $result=$paginator->paginate(
            $query,
            $request->query->getInt('page',1),10

        );
        $SommeTraité=0;
        $SommeNonTraité=0;
        $sommeReclamation=0;
        $TAutre=0;
        $TPC=0;
        $TPCMD=0;
        $NAutre=0;
        $NPC=0;
        $NPCMD=0;


        foreach($reclamation as $elt) {
            $sommeReclamation=$sommeReclamation+1;
            if($elt->getEtatr()=="traiter") {
                $SommeTraité = $SommeTraité + 1;
                if($elt->getIdcatrec()->getNom()=="Problème de Compte"){
                    $TPC=$TPC+1;}
                if( $elt->getIdcatrec()->getNom()=="Autre"){
                    $TAutre=$TAutre+1;
                }
                if( $elt->getIdcatrec()->getNom()=="Problème de Commande"){
                    $TPCMD=$TPCMD+1;
                }
            }
            if($elt->getEtatr()=="non traiter") {
                $SommeNonTraité = $SommeNonTraité + 1;
                // $NPC=$NPC+1;
                if($elt->getIdcatrec()->getNom()=="Problème de Compte"){
                    $NPC=$NPC+1;}

                if($elt->getIdcatrec()->getNom()=="Autre"){
                    $NAutre=$NAutre+1;}
                if( $elt->getIdcatrec()->getNom()=="Problème de Commande"){
                    $NPCMD=$NPCMD+1;
                }
            }


        }

        return $this->render('@minipo/Reclamation/AffichageReclamation.html.twig',array('reclamation'=>$result ,'total'=>$sommeReclamation,'sommetraité'=>$SommeTraité , 'sommenontraité'=>$SommeNonTraité,'TPC'=>$TPC,
            'TPCMD'=>$TPCMD,'TAutre'=>$TAutre,'NPC'=>$NPC,'NAutre'=>$NAutre,'NPCMD'=>$NPCMD));
    }
    public function showdetailedClientAction(Request $request,$id)
    {
        //************Panier***************
        $idUser=$this->getUser()->getId();
        $repo=$this->getDoctrine()->getManager()->getRepository(Lignecommande::class);
        $Panier=$repo->myFindPanier($idUser);
        //************************************************************************

        $em=$this->getDoctrine()->getManager();
        $reclamation=$em->getRepository((Reclamation::class))->find($id);
        if($request->isMethod('POST')){
            if($reclamation->getEtatr()=="non traiter"){
                $reclamation->getObjet();
                $reclamation->setdescription($request->get('description'));

                $em->flush();
            }
            return $this->redirectToRoute('minipo_AfficherMesReclamation');
        }
        return $this->render('@minipo/Reclamation/DetailReclamationClient.html.twig',array('reclamation'=>$reclamation,'lc'=>$Panier));
    }

    /*public function showdetailedClientAction(Request $request,$id)
    {
        $em=$this->getDoctrine()->getManager();
        $reclamation=$em->getRepository((Reclamation::class))->find($id);
        if($request->isMethod('POST')){
            if($reclamation->getEtatr()=="non traiter"){
                $reclamation->getObjet();
                $reclamation->setdescription($request->get('description'));
                $em->flush();
            }
            return $this->redirectToRoute('minipo_AfficherMesReclamation');
        }
        return $this->render('@minipo/Reclamation/DetailReclamationClient.html.twig',array('reclamation'=>$reclamation));
    }*/

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
    public function showdetailedAction($id)
    {
        $em= $this->getDoctrine()->getManager();
        $p=$em->getRepository('minipoBundle:Reclamation')->find($id);
        return $this->render('@minipo/Reclamation/AffichageReclamation.html.twig', array(
            'type'=>$p->getType(),
            'objet'=>$p->getObjet(),
            'description'=>$p->getDescription(),
            'Reponse'=>$p->getReponse(),
            'etatr'=>$p->getEtatr(),
            'idr'=>$p->getIdr()
        ));
    }
}



