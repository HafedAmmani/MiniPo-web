<?php

namespace minipoBundle\Controller;

use minipoBundle\Entity\Livraison;
use minipoBundle\Form\LivraisonType;
use minipoBundle\Form\UpdateLType;
use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class LivraisonBackController extends Controller
{
    public function afficheLivAction(Request $request){
        $repository=$this->getDoctrine()->getManager()->getRepository(Livraison::class);
        $listliv=$repository->findLivraison();
        $paginator = $this->get('knp_paginator');
        $liste  = $paginator->paginate(
            $listliv,
            $request->query->get('page', 1)/*le numéro de la page à afficher*/,
            2/*nbre d'éléments par page*/
        );
        return($this->render('@minipo/Livraison/livraison.html.twig',array("listeliv"=>$liste)));
    }
    public function ajoutlivAction(Request $request){
        $livraison= new Livraison();
        $form = $this->createForm(LivraisonType::class,$livraison);
        $form = $form->handleRequest($request);
        if($form->isSubmitted() and $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($livraison);
            $em->flush();
            $livraison->setMatriculel("X".$livraison->getIdliv());
            $em->flush();
            return $this->redirectToRoute('minipo_afficheLiv');}
        return $this->render( '@minipo/Livraison/ajoutliv.html.twig', array('f'=>$form->createView()));

    }
    public function updateLivAction(Request $request,$id){
        $em= $this->getDoctrine()->getManager();
        $liv = $em->getRepository(Livraison::class)
            ->find($id);
        $form = $this->createForm(UpdateLType::class, $liv);
        $form = $form->handleRequest($request);
        if($form->isSubmitted()){
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $data = $em->getRepository(Livraison::class)
                ->getEmailData($liv->getIdc(),$liv->getIdliv());
            $message = (new Swift_Message('Order Confirmation'))
                ->setFrom('projetminipo@gmail.com')
                ->setTo('projetminipo@gmail.com')
                ->setBody(
                    $this->renderView(
                    // app/Resources/views/Emails/registration.html.twig
                        '@minipo/Livraison/email.html.twig',
                        ['data' => $data]
                    ),
                    'text/html'
                );

            $this->get('mailer')->send($message);
            return $this->redirectToRoute('minipo_afficheLiv');
        }
        return $this->render('@minipo/Livraison/update.html.twig',array('f'=>$form->createView(),'liv'=>$liv));
    }
    public function DeleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $liv = $em->getRepository(Livraison::class)->find($id);

        $em->remove($liv);
        $em->flush();
        return $this->redirectToRoute('minipo_afficheLiv');
    }

    public function mailAction($name)
    {
        $message = (new Swift_Message('Hello Email'))
            ->setFrom('projetminipo@gmail.com')
            ->setTo('projetminipo@gmail.com')
            ->setBody(
                $this->renderView(
                // app/Resources/views/Emails/registration.html.twig
                    '@minipo/Livraison/email.html.twig',
                    ['name' => $name]
                ),
                'text/html'
            );

        $this->get('mailer')->send($message);

        // or, you can also fetch the mailer service this way
        // $this->get('mailer')->send($message);

        return new Response('<html><body>test</body></html>');
    }
    public function detailsAction($id)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository(Livraison::class);
        $liv=$repository->find($id);
        return ($this->render('@minipo/Livraison/details.html.twig', array("liv" => $liv)));
    }
}

