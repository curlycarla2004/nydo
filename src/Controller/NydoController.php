<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Entity\Images;
use App\Form\ArticlesType;
use App\Repository\ArticlesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class NydoController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(ArticlesRepository $articlesRepository) : Response
    {
        return $this->render('site/home.html.twig', [
            'articles' => $articlesRepository->findAll(),
            ]);
    }

    /**
     * @Route("/realizace/{slug}", name="realizace")
     * 
     */
    public function show(Articles $article) : Response
    {
        return $this->render('site/realizace.html.twig', [
            'article' => $article,
            ]);
    }

    public function header($currentPage){
        return $this->render('site/header.html.twig' , [
            'currentPage' => $currentPage,
            'linkScroll' => $currentPage == 'home' ? '.smoothScroll' : '',
        ]);
    }
}
