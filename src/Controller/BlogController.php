<?php
namespace App\Controller;
use App\Entity\Blogs;
use App\Entity\Comments;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class BlogController extends AbstractController {
    /**
     * @Route("/")
     * @Method({"GET"})
     */
    public function index(){
       $blogs = $this->getDoctrine()->getRepository(Blogs::class)->findAll();
       $form = $this->createFormBuilder(null)
       ->add('q',TextType::class, ['label' => 'Search','attr'=>['class'=>'form-control'],'required' => true])
       ->add('search',SubmitType::class, ['label'=>'Search','attr' => ['class' => 'btn btn-primary']])
       ->setAction($this->generateUrl('search'))
       ->setMethod('GET')
       ->getForm();
       return $this->render('blogs/index.html.twig',['blogs' => $blogs, 'form' => $form->createView()]);
    }
    
    /**
     * @Route("/blog/{id}")
     * @Method({"GET","POST"})
     */
    public function show(Request $request,$id) {
        $blog = $this->getDoctrine()->getRepository(Blogs::class)->find($id);
        $comments = $this->getDoctrine()->getRepository(Comments::class)->findByBlogId($id);
        $form = $this->createFormBuilder(null)
            ->add('comment',TextType::class, ['label' => 'Add a comment','attr'=>['class'=>'form-control'],'required' => true])
            ->add('post',SubmitType::class, ['label'=>'Post','attr' => ['class' => 'btn btn-primary','onclick' => "addComment(); return false;"]])
            ->setMethod('POST')
            ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $entityManager = $this->getDoctrine()->getManager();
            $c = $form->getData();
            $comment = new Comments();
            $comment->setComment($c['comment']);
            $comment->setBlog($blog);
            $comment->setCreated(new \DateTime(date('Y-m-d H:i:s')));
            $entityManager->persist($comment);
            $entityManager->flush();
        }

        return $this->render('blogs/show_blog.html.twig', ['blog' => $blog,'comments' => $comments, 'form' => $form->createView()]);
    }
   
}