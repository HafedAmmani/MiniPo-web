<?php

namespace minipoBundle\Controller;
use http\Env\Response;
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
    public function AjouterReclamationEmployeAction(Request $request,$id=15)
    {
        $reclamation=new Reclamationemploye();
        $form=$this->createForm(ReclamationemployeType::class,$reclamation);
        $form=$form->handleRequest($request);
        if($form->isSubmitted() and $form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $file=$reclamation->getImage();
            $filename= md5(uniqid()) . '.' . $file->guessExtension();
            $file->move($this->getParameter('photos_directory'), $filename);
            $reclamation->setImage($filename);
            $reclamation->setEtatremp("non traiter");
            $em->persist($reclamation);
            $em->flush();
            $this->addFlash('info', 'Reclamation envoyee !');

            return $this->redirectToRoute('minipo_AfficherToutesReclamationEmploye');
            }
        return $this->render('@minipo/Reclamation/AjouterReclamationEmploye.html.twig',array('f'=>$form->createView()));
    }
    public function ModifierEtatReclamationEmployeAction(Request $request,$id)
    {
        $em=$this->getDoctrine()->getManager();
        $reclamation=$em->getRepository((Reclamationemploye::class))->find($id);
        if($request->isMethod('POST')){
            $reclamation->setEtatremp($request->get('etat'));
            $reclamation->setReponse($request->get('reponse'));

            $em->flush();
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
        return $this->render('@minipo/Reclamation/index.html.twig');
    }
    public function AfficherToutesReclamationEmployeAction()
    {
        $reclamation = $this->getDoctrine()->getRepository(Reclamationemploye::class)->findAll();
        return $this->render('@minipo/Reclamation/AffichageMesReclamationEmploye.html.twig',array('reclamationemploye'=>$reclamation));
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
    public function ajaxListAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $searchParam = $request->get('searchParam');
        $entities = $em->getRepository('minipoBundle:Reclamationemploye')->search($searchParam);
        return $this->render('@minipo/Reclamation/AffichageReclamationEmploye.html.twig', array(
            'entities' => $entities,

        ));
    }
}
