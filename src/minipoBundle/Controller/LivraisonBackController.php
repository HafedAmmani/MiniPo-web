<?php

namespace minipoBundle\Controller;

use minipoBundle\Entity\Livraison;
use minipoBundle\Form\LivraisonType;
use minipoBundle\Form\UpdateType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class LivraisonBackController extends Controller
{
    public function afficheLivAction(){
        $repository=$this->getDoctrine()->getManager()->getRepository(Livraison::class);
        $listliv=$repository->findLivraison();
        return($this->render('@minipo/Livraison/livraison.html.twig',array("listeliv"=>$listliv)));
    }
    public function ajoutlivAction(Request $request){
        $livraison= new Livraison();
        $form = $this->createForm(LivraisonType::class,$livraison);
        $form = $form->handleRequest($request);
        if($form->isSubmitted() and $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($livraison);
            $em->flush();
            return $this->redirectToRoute('minipo_afficheLiv');}
        return $this->render( '@minipo/Livraison/ajoutliv.html.twig', array('f'=>$form->createView()));

    }
    public function updateLivAction(Request $request,$id){
        $em= $this->getDoctrine()->getManager();
        $liv = $em->getRepository(Livraison::class)
            ->find($id);
        $form = $this->createForm(UpdateType::class, $liv);
        $form = $form->handleRequest($request);
        if($form->isSubmitted()){
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('minipo_afficheLiv');
        }
        return $this->render('@minipo/Livraison/update.html.twig',array('f'=>$form->createView()));
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
        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('projetminipo@gmail.com')
            ->setTo('projetminipo@gmail.com')
            ->setBody(
                $this->renderView(
                // app/Resources/views/Emails/registration.html.twig
                    '@minipo/Livraison/email.html.twig',
                    ['name' => $name]
                ),
                'text/html'
            )

            // you can remove the following code if you don't define a text version for your emails
            ->addPart(
                $this->renderView(
                    '@minipo/Livraison/email.html.twig',
                    ['name' => $name]
                ),
                'text/plain'
            )
        ;

        $this->get('mailer')->send($message);

        // or, you can also fetch the mailer service this way
        // $this->get('mailer')->send($message);

        return new Response('<html><body>test</body></html>');
    }
}
