<?php

namespace minipoBundle\Controller;
use minipoBundle\Entity\Commentaire;
use minipoBundle\Entity\CommentaireRep;
use minipoBundle\Entity\Lignecommande;
use minipoBundle\Form\Add;
use minipoBundle\Entity\Articles;
use minipoBundle\Form\AddComment;
use minipoBundle\Form\AddRepComment;
use minipoBundle\Form\BlogType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;



class BlogController extends Controller
{
    /* public function addBlogAction(Request $request)
        {
            //$id=$this->getUser()->getId();
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
                $article->setCreator($this->getUser());
                $article->setdate(new \DateTime('now'));
                $article->setId($classCat);
                $em->persist($article);
                $em->flush();

                $this->addFlash('info', 'Created Successfully !');
            }
            return $this->render('@minipo/Blog/add.html.twig', array(
                "Form"=> $form->createView()
            ));
        }*/

    public function addBlogAction(Request $request)
    {
        $id = $this->getUser()->getId();
        $article = new Articles();
        $form = $this->createForm(Add::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $classCat = $em->getRepository('minipoBundle:User')->find($id);
            $file = $article->getImageFile();
            $filename = md5(uniqid()) . '.' . $file->guessExtension();
            // $file->move($this->getParameter('photos_directory'), $filename);
            $article->setImageName($filename);
            $article->setdate(new \DateTime('now'));
            $article->setId($classCat);
            $em->persist($article);
            $article->setFirstname($classCat->getFirstname());
            $article->setLastname($classCat->getLastname());
            $em->flush();

            $this->addFlash('info', 'Created Successfully !');
        }
        return $this->render('@minipo/Blog/add.html.twig', array(
            "Form" => $form->createView(), $id
        ));
    }

    public function listBlogAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $blogs = $em->getRepository('minipoBundle:Articles')->findAll();
        return $this->render('@minipo/Blog/list.html.twig', array(
            "blogs" => $blogs
        ));

    }

    public function listBlogExterneAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $blogs = $em->getRepository('minipoBundle:Articles')->findAll();
        return $this->render('@minipo/Blog/listBlogClientEx.html.twig', array(
            "blogs" => $blogs
        ));

    }


    public function listBlogClientAction(Request $request)
    {
        //************Panier***************
        $id=$this->getUser()->getId();
        $repo=$this->getDoctrine()->getManager()->getRepository(Lignecommande::class);
        $Panier=$repo->myFindPanier($id);
        //************************************************************************
        $em = $this->getDoctrine()->getManager();
        $blogs = $em->getRepository('minipoBundle:Articles')->findAll();
        return $this->render('@minipo/Blog/listBlogClient.html.twig', array(
            "blogs" => $blogs,'lc'=>$Panier
        ));

    }


    public function updateBlogAction(Request $request, $idA)
    {
        $em = $this->getDoctrine()->getManager();
        $p = $em->getRepository('minipoBundle:Articles')->find($idA);
        $form = $this->createForm(Add::class, $p);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $file = $p->getImageFile();
            $filename = md5(uniqid()) . '.' . $file->guessExtension();
            // $file->move($this->getParameter('photos_directory'), $filename);
            $p->setImageName($filename);
            $p->setdate(new \DateTime('now'));
            $em = $this->getDoctrine()->getManager();
            $em->persist($p);
            $em->flush();
            return $this->redirectToRoute('minipo_listblog');

        }
        return $this->render('@minipo/Blog/update.html.twig', array(
            "form" => $form->createView()
        ));
    }

    public function deleteBlogAction(Request $request)
    {
        $idA = $request->get('idA');
        $em = $this->getDoctrine()->getManager();
        $Blog = $em->getRepository('minipoBundle:Articles')->find($idA);
        $em->remove($Blog);
        $em->flush();
        return $this->redirectToRoute('minipo_listblog');
    }

    public function detailBlogAction(Request $request, $idA)
    {
        $id = $this->getUser()->getId();
        $comment = new Commentaire();
        $form = $this->createForm(AddComment::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $classCat = $em->getRepository('minipoBundle:User')->find($id);
            $classA = $em->getRepository('minipoBundle:Articles')->find($idA);
            $comment->setId($classCat);
            $comment->setIda($classA);
            $comment->setFirstname($classCat->getFirstname());
            $comment->setLastname($classCat->getLastname());
            $em->persist($comment);
            $em->flush();
            $this->addFlash('info', 'Created Successfully !');
        }
        $em = $this->getDoctrine()->getManager();
        $p = $em->getRepository('minipoBundle:Articles')->find($idA);
        $repository = $this->getDoctrine()->getManager()->getRepository(Commentaire::class);
        $commentt = $repository->findcomment($idA);
        return $this->render('@minipo/Blog/showDetailed.html.twig', array(
            'Titre' => $p->getTitre(),
            'Date' => $p->getdate(),
            'Image' => $p->getImage(),
            'Descripion' => $p->getDescription(),
            'blogs' => $p,
            'idA' => $p->getIda(),
            "Form" => $form->createView(),
            "com" => $commentt,


        ));
    }

    public function detailBlogClientAction(Request $request, $idA)
    {

        //************Panier***************
        $id=$this->getUser()->getId();
        $repo=$this->getDoctrine()->getManager()->getRepository(Lignecommande::class);
        $Panier=$repo->myFindPanier($id);
        //************************************************************************

        $comment = new Commentaire();
        $form = $this->createForm(AddComment::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $classCat = $em->getRepository('minipoBundle:User')->find($id);
            $classA = $em->getRepository('minipoBundle:Articles')->find($idA);
            $comment->setId($classCat);
            $comment->setIda($classA);
            $comment->setFirstname($classCat->getFirstname());
            $comment->setLastname($classCat->getLastname());
            $em->persist($comment);
            $em->flush();
            $this->addFlash('info', 'Created Successfully !');
        }
        $em = $this->getDoctrine()->getManager();
        $p = $em->getRepository('minipoBundle:Articles')->find($idA);
        $repository = $this->getDoctrine()->getManager()->getRepository(Commentaire::class);
        $commentt = $repository->findcomment($idA);
        return $this->render('@minipo/Blog/showDetailedClient.html.twig', array(
            'Titre' => $p->getTitre(),
            'Date' => $p->getdate(),
            'Image' => $p->getImage(),
            'Descripion' => $p->getDescription(),
            'blogs' => $p,
            'idA' => $p->getIda(),
            "Form" => $form->createView(),
            "com" => $commentt,
            'lc'=>$Panier,


        ));
    }




    public function detailBlogClientExAction(Request $request, $idA)
    {
        $comment = new Commentaire();
        $form = $this->createForm(AddComment::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $classA = $em->getRepository('minipoBundle:Articles')->find($idA);
            $comment->setIda($classA);
            $em->persist($comment);
            $em->flush();
            $this->addFlash('info', 'Created Successfully !');
        }
        $em = $this->getDoctrine()->getManager();
        $p = $em->getRepository('minipoBundle:Articles')->find($idA);
        $repository = $this->getDoctrine()->getManager()->getRepository(Commentaire::class);
        $commentt = $repository->findcomment($idA);
        return $this->render('@minipo/Blog/showDetailedClientExt.html.twig', array(
            'Titre' => $p->getTitre(),
            'Date' => $p->getdate(),
            'Image' => $p->getImage(),
            'Descripion' => $p->getDescription(),
            'blogs' => $p,
            'idA' => $p->getIda(),
            "Form" => $form->createView(),
            "com" => $commentt,


        ));
    }

    /*

        public function detailBlogClientAction(Request $request, $idA)
        {
            $id=$this->getUser()->getId();
            $comment = new Commentaire();
            $form= $this->createForm(AddComment::class,$comment);
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid())
            {
                $em = $this->getDoctrine()->getManager();
                $classCat = $em->getRepository('minipoBundle:User')->find($id);
                $classA = $em->getRepository('minipoBundle:Articles')->find($idA);
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
            $repository=$this->getDoctrine()->getManager()->getRepository(Commentaire::class);
            $commentt = $repository->findcomment($idA);
            return $this->render('@minipo/Blog/showDetailed.html.twig', array(
                'Titre'=>$p->getTitre(),
                'Date'=>$p->getdate(),
                'Image'=>$p->getImage(),
                'Descripion'=>$p->getDescription(),
                'blogs'=>$p,
                'idA'=>$p->getIda(),
                "Form"=> $form->createView(),
                "com"=>$commentt,


            ));
        }
    */

    public function deleteCommentaireAction(Request $request, $idA)
    {
        $id = $this->getUser()->getId();
        $idcom = $request->get('idcom');
        $em = $this->getDoctrine()->getManager();
        $blogs = $em->getRepository('minipoBundle:Commentaire')->find($idcom);
        $em->remove($blogs);
        $em->flush();
        return $this->redirectToRoute('minipo_DetaileBlog', array(
            'idA' => $idA));
    }

    public function AddComentResponseAction(Request $request, $idA,$idcom)
    {
        $id = $this->getUser()->getId();
        $comment = new CommentaireRep();
        $form = $this->createForm(AddRepComment::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $classCat = $em->getRepository('minipoBundle:User')->find($id);
            $classA = $em->getRepository('minipoBundle:Articles')->find($idA);
            $classC = $em->getRepository('minipoBundle:Commentaire')->find($idcom);
            $comment->setId($classCat);
            $comment->setIda($classA);

            $comment->setFirstname($classCat->getFirstname());
            $comment->setLastname($classCat->getLastname());
            $em->persist($comment);
            $em->flush();
            $this->addFlash('info', 'Created Successfully !');
        }
        $em = $this->getDoctrine()->getManager();
        $p = $em->getRepository('minipoBundle:Articles')->find($idA);
        $repository = $this->getDoctrine()->getManager()->getRepository(Commentaire::class);
        $commentt = $repository->findcomment($idA);
        return $this->render('@minipo/Blog/showDetailed.html.twig', array(
            'Titre' => $p->getTitre(),
            'Date' => $p->getdate(),
            'Image' => $p->getImage(),
            'Descripion' => $p->getDescription(),
            'blogs' => $p,
            'idA' => $p->getIda(),
            "Form" => $form->createView(),
            "com" => $commentt,


        ));
    }

    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $requestString = $request->get('q');
        $posts =  $em->getRepository('minipoBundle:Articles')->findEntitiesByString($requestString);
        if(!$posts) {
            $result['posts']['error'] = "blog Not found :( ";
        } else {
            $result['posts'] = $this->getRealEntities($posts);
        }
        return new Response(json_encode($result));
    }
    public function getRealEntities($posts){
        foreach ($posts as $posts){
            $realEntities[$posts->getIda()] = [$posts->getImageName(),$posts->getTitre()];

        }
        return $realEntities;
    }




}