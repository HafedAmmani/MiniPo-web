<?php

namespace minipoBundle\Controller;
use Doctrine\ORM\Mapping\Id;
use http\Env\Response;
use minipoBundle\Entity\Reclamation;
use minipoBundle\Form\ReclamationType;
use minipoBundle\Form\SearchReclamationType;
use minipoBundle\minipoBundle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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
        $reclamation=new Reclamation();
        $form=$this->createForm(ReclamationType::class,$reclamation);
        $form=$form->handleRequest($request);
        if($form->isSubmitted() and $form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $file=$reclamation->getImage();
            $filename= md5(uniqid()) . '.' . $file->guessExtension();
            $file->move($this->getParameter('photos_directory'), $filename);
            $reclamation->setImage($filename);
            $reclamation->setEtatr("non traiter");
            $em->persist($reclamation);
            $em->flush();

            return $this->redirectToRoute('minipo_AfficherMesReclamation');}
        return $this->render('@minipo/Reclamation/AjouterReclamationClient.html.twig',array('f'=>$form->createView()));
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
            $reclamation->setEtatr($request->get('etat'));
            $reclamation->setReponse($request->get('reponse'));

            $em->flush();
            return $this->redirectToRoute('minipo_AfficherToutesReclamation');
        }
        return $this->render('@minipo/Reclamation/ModifierEtatReclamation.html.twig',array('reclamation'=>$reclamation));
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
        $session = $request->getSession();
        $id=$session->get('id');
        $reclamation = $this->getDoctrine()->getRepository(Reclamation::class)->findOneBy(array('id'=>$id));
        return $this->render('@minipo/Reclamation/AffichageReclamation.html.twig',array('reclamation'=>$reclamation));
        }



    public function AfficherToutesReclamationAction()
    {
        $reclamation = $this->getDoctrine()->getRepository(Reclamation::class)->findAll();
        return $this->render('@minipo/Reclamation/AffichageMesReclamation.html.twig',array('reclamation'=>$reclamation));
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



