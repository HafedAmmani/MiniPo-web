<?php

namespace minipoBundle\Controller;
use minipoBundle\Entity\Commentaire;
use minipoBundle\Form\Add;
use minipoBundle\Entity\Articles;
use minipoBundle\Form\AddComment;
use minipoBundle\Form\BlogType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;



class BlogController extends Controller
{
    public function addBlogAction(Request $request)
    {
        $id=$this->getUser()->getId();
        $article = new Articles();
        $form= $this->createForm(Add::class,$article);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $classCat = $em->getRepository('minipoBundle:User')->find($id);
            $file = $article->getImage();
            $filename= md5(uniqid()) . '.' . $file->guessExtension();
            $file->move($this->getParameter('photos_directory'), $filename);
            $article->setImage($filename);
          //  $article->setCreator($this->getUser());
            $article->setdate(new \DateTime('now'));
            $article->setId($classCat);
            $em->persist($article);
            $em->flush();

            $this->addFlash('info', 'Created Successfully !');
        }
        return $this->render('@minipo/Blog/add.html.twig', array(
            "Form"=> $form->createView()
        ));
    }
    public function listBlogAction(Request $request)
    {

        $em=$this->getDoctrine()->getManager();
        $blogs=$em->getRepository('minipoBundle:Articles')->findAll();
        return $this->render('@minipo/Blog/list.html.twig', array(
            "blogs" =>$blogs
        ));

    }

    public function updateBlogAction(Request $request, $idA)
    {
        $em=$this->getDoctrine()->getManager();
        $p= $em->getRepository('minipoBundle:Articles')->find($idA);
        $form=$this->createForm(Add::class,$p);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $file = $p->getImage();
            $filename= md5(uniqid()) . '.' . $file->guessExtension();
            $file->move($this->getParameter('photos_directory'), $filename);
            $p->setImage($filename);
            $p->setdate(new \DateTime('now'));
            $em= $this->getDoctrine()->getManager();
            $em->persist($p);
            $em->flush();
            return $this->redirectToRoute('minipo_listblog');

        }
        return $this->render('@minipo/Blog/update.html.twig', array(
            "form"=> $form->createView()
        ));
    }
    public function deleteBlogAction(Request $request)
    {
        $idA = $request->get('idA');
        $em= $this->getDoctrine()->getManager();
        $Blog=$em->getRepository('minipoBundle:Articles')->find($idA);
        $em->remove($Blog);
        $em->flush();
        return $this->redirectToRoute('minipo_listblog');
    }
    public function detailBlogAction(Request $request, $idA)
    {

        $id=$this->getUser()->getId();

        //$ida=$this->getIda();
        $comment = new Commentaire();
        $form= $this->createForm(AddComment::class,$comment);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $classCat = $em->getRepository('minipoBundle:User')->find($id);
            $classA = $em->getRepository('minipoBundle:Articles')->find($idA);
            //  $article->setCreator($this->getUser());
            //  $article->setdate(new \DateTime('now'));
            $comment->setId($classCat);
            $comment->setIda($classA);
            $comment->setFirstname($classCat->getFirstname());
            $comment->setLastname($classCat->getLastname());
            $em->persist($comment);
            $em->flush();
            $this->addFlash('info', 'Created Successfully !');
        }

        $em= $this->getDoctrine()->getManager();
        $p=$em->getRepository('minipoBundle:Articles')->find($idA);
        //$em= $this->getDoctrine()->getManager();



        $repository=$this->getDoctrine()->getManager()->getRepository(Commentaire::class);
        $commentt = $repository->findcomment($idA);
        return $this->render('@minipo/Blog/showDetailed.html.twig', array(
            'Titre'=>$p->getTitre(),
            'Date'=>$p->getdate(),
            'Image'=>$p->getImage(),
            'Descripion'=>$p->getDescription(),
            'blogs'=>$p,
            'idA'=>$p->getIda(),
           // 'description'=>$p->getIda()->getDescription(),
            "Form"=> $form->createView(),
            "com"=>$commentt,


        ));
    }

   public function deleteCommentaireAction(Request $request,$idA)
    {
        $idcom = $request->get('idcom');
        $em= $this->getDoctrine()->getManager();
        $blogs=$em->getRepository('minipoBundle:Commentaire')->find($idcom);
        $em->remove($blogs);
        $em->flush();
        return $this->redirectToRoute('minipo_DetaileBlog',array(
            'idA' => $idA));
    }

    /*
    public function deleteCommentaireAction(Request $request)
    {
        $idcom = $request->get('idcom');
        $em=$this->getDoctrine()->getManager();
        $comment=$em->getRepository('minipoBundle:Commentaire')->find($idcom);
        $em->remove($comment);
        $em->flush();
        return $this->redirectToRoute("minipo_DetaileBlog");
    }
*/

}
