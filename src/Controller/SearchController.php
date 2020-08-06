<?php

namespace App\Controller;
use App\Entity\Blogs;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\ORM\EntityManagerInterface;

class SearchController extends AbstractController
{
    /**
     * @Route("/search", name="search")
     */
    public function index(Request $request)
    {
        
        $form = $this->createFormBuilder(null)
        ->add('q',TextType::class, ['label' => 'Search','attr'=>['class'=>'form-control'],'required' => true])
        ->add('search',SubmitType::class, ['label'=>'Search','attr' => ['class' => 'btn btn-primary']])
        ->setMethod('GET')
        ->getForm();
        
        $f = $request->query->get('form');
        $blogs = $this->getDoctrine()->getRepository(Blogs::class)->searchBlogs($f['q']);

        return $this->render('blogs/search.html.twig',['blogs' => $blogs,'form' => $form->createView()]);
     
    }

}
