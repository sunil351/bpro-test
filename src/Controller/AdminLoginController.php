<?php
namespace App\Controller;

use App\Entity\Users;
use App\Entity\Blogs;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AdminLoginController extends AbstractController {


    public function __construct(Security $security)
    {
        
        $this->security = $security;
    }

    /**
     * @Route("/admin", name="admin")
     * @Method({"GET"})
     */
    public function index(){
       // print_r($this->security->getUser());
       $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
       $blogs = $this->getDoctrine()->getRepository(Blogs::class)->findAll();
       return $this->render('admin/index.html.twig',['blogs' => $blogs]);
    }
    /**
     * @Route("/admin/blogs/add")
     * @Method({"GET","POST"})
     */
    public function add_blog(Request $request){
        $blog = new Blogs();
        $form = $this->createFormBuilder($blog)
        ->add('title',TextType::class, ['attr'=>['class'=>'form-control'],'required' => true])
        ->add('body',TextAreaType::class, ['attr'=> ['class' => 'form-control'],'required' => true])
        ->add('save',SubmitType::class, ['label'=>'Create','attr' => ['class' => 'btn btn-primary']])
        ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $blog = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($blog);
            $entityManager->flush();

            return $this->redirectToRoute('admin');
        }
        return $this->render('admin/add_new_blog.html.twig',['form' => $form->createView()]);
    }

    /**
     * @Route("/admin/blog/edit/{id}", name="edit_blog")
     * Method({"GET", "POST"})
     */
    public function edit(Request $request, $id) {
        $blog = new Blogs();
        $blog = $this->getDoctrine()->getRepository(Blogs::class)->find($id);
  
        $form = $this->createFormBuilder($blog)
          ->add('title', TextType::class, ['attr' => ['class' => 'form-control']])
          ->add('body', TextareaType::class, [
            'attr' => ['class' => 'form-control']
          ])
          ->add('save', SubmitType::class, [
            'label' => 'Update',
            'attr' => ['class' => 'btn btn-primary mt-3']
          ])
          ->getForm();
  
        $form->handleRequest($request);
  
        if($form->isSubmitted() && $form->isValid()) {
  
          $entityManager = $this->getDoctrine()->getManager();
          $entityManager->flush();
  
          return $this->redirectToRoute('admin');
        }
  
        return $this->render('admin/edit_blog.html.twig', array(
          'form' => $form->createView()
        ));
      }
  
      /**
       * @Route("/admin/blog/{id}", name="blog_show")
       */
      public function show($id) {
        $blog = $this->getDoctrine()->getRepository(Blogs::class)->find($id);
  
        return $this->render('admin/show_blog.html.twig', array('blog' => $blog));
      }
  
      /**
       * @Route("/admin/blog/delete/{id}")
       * @Method({"DELETE"})
       */
      public function delete(Request $request, $id) {
        $blog = $this->getDoctrine()->getRepository(Blogs::class)->find($id);
  
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($blog);
        $entityManager->flush();
  
        $response = new Response();
        $response->send();
        return $this->redirectToRoute('admin');
      }
  
}