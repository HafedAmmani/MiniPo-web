<?php

namespace minipoBundle\Controller;

use minipoBundle\Entity\Livraison;
use minipoBundle\Form\RechercheDestType;
use minipoBundle\Form\RechercheLType;
use minipoBundle\Form\UpdateEtatType;
use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class MobileController extends Controller
{
    public function affichLivAction()
    {
        $repository = $this->getDoctrine()->getManager()->getRepository(Livraison::class);
        $listliv = $repository->findLivreurlist(55);
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($listliv);
        return new JsonResponse($formatted);
    }

    public function detailAction(Request $request)
    {
        $liv = new Livraison();
        $em = $this->getDoctrine()->getManager();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $id=$request->query->get('id');
        $liv = $em->getRepository(Livraison::class)->findLivraisonById($id);
        $formatted = $serializer->normalize($liv);
        return new JsonResponse($formatted);
    }

    public function updateEtatAction(Request $request)
    {
        $liv = new Livraison();
        $em = $this->getDoctrine()->getManager();
        $params = array();
        $content = $request->getContent();
        $serializer = new Serializer([new ObjectNormalizer()]);
        if (!empty($content)) {
            $params = json_decode($content, true);
            if(isset($params['id']) && isset($params['etatl'])){
                $liv = $em->getRepository(Livraison::class)
                    ->find($params['id']);
                $liv->setEtatl($params['etatl']);
                $em = $this->getDoctrine()->getManager();
                $em->flush();
                $msg = array();
                $msg['message'] = "Updated";
                $formatted = $serializer->normalize($msg);
                $data = $em->getRepository(Livraison::class)
                    ->getEmailData($liv->getIdc(),$liv->getIdliv());
                if($params['etatl'] == "refusee")
                    $message = (new Swift_Message('Order Confirmation'))
                        ->setFrom('projetminipo@gmail.com')
                        ->setTo('projetminipo@gmail.com')
                        ->setBody(
                            $this->renderView(
                            // app/Resources/views/Emails/registration.html.twig
                                '@minipo/Livraison/emailRefusee.html.twig',
                                ['data' => $data]
                            ),
                            'text/html'
                        );
                else
                    $message = (new Swift_Message('Order Confirmation'))
                        ->setFrom('projetminipo@gmail.com')
                        ->setTo('projetminipo@gmail.com')
                        ->setBody(
                            $this->renderView(
                            // app/Resources/views/Emails/registration.html.twig
                                '@minipo/Livraison/emailLivree.html.twig',
                                ['data' => $data]
                            ),
                            'text/html'
                        );
                $this->get('mailer')->send($message);
                return new JsonResponse($formatted);
            }
        }
        $errormsg= array();
        $errormsg['message'] = "error in params";
        $formatted = $serializer->normalize($errormsg);
        return new JsonResponse($formatted);
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
    public function searchlAction(Request $request){
        $params = array();
        $content = $request->getContent();
        if (!empty($content))
        {
            $params = json_decode($content, true);
        }
        $em = $this->getDoctrine()->getManager();
        if($params['etatl'] === "all")
            if (!isset($params['destination']))
                $livs = $em->getRepository(Livraison::class)->findBy(array('id' => 55));
            else
                $livs = $em->getRepository(Livraison::class)->searchLiv(55 , $params["etatl"],$params["destination"]);
        else
            if (!isset($params['destination']))
                $livs = $em->getRepository(Livraison::class)->findBy(array('id' => 55,'etatl' => $params["etatl"]));
            else
                $livs = $em->getRepository(Livraison::class)->searchLiv(55 , $params["etatl"],$params["destination"]);
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($livs);
        return new JsonResponse($formatted);
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
    public function mailLAction($name)
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
}
