<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Entity\Images;
use App\Form\ArticlesType;
use App\Repository\ArticlesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/articles')]
class ArticlesController extends AbstractController
{
    

    #[Route('/', name: 'articles_index', methods: ['GET'])]
    public function index(ArticlesRepository $articlesRepository): Response
    {
        return $this->render('articles/index.html.twig', [
            'articles' => $articlesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'articles_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $article = new Articles();
        $form = $this->createForm(ArticlesType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //on recuperer les images transmises
            $images = $form->get('images')->getData();
            $featured_image = $form->get('featured_image')->getData();

            //on boucles sur les images
            foreach ($images as $image) {
                //on genere un nouveau nom de fichier
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();

                //on copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );

                //on stocke l'image dans la base de données(son nom)
                $img = new Images();
                $img->setName($fichier);
                $article->addImage($img);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

            //on boucles sur les images
            foreach ($featured_image as $image) {
                //on genere un nouveau nom de fichier
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();

                //on copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );

                //on stocke l'image dans la base de données(son nom)
                $img = new Images();
                $img->setName($fichier);
                $article->addImage($img);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('articles_index');
        }

        return $this->render('articles/new.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'articles_show', methods: ['GET'])]
    public function show(Articles $article): Response
    {
        
        return $this->render('articles/show.html.twig', [
            'article' => $article,
        ]);
    }

    #[Route('/{id}/edit', name: 'articles_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Articles $article): Response
    {
        $form = $this->createForm(ArticlesType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //on recuperer les images transmises
            $images = $form->get('images')->getData();
            //on boucles sur les images
            foreach ($images as $image) {
                //on genere un nouveau nom de fichier
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();

                //on copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );

                //on stocke l'image dans la base de données(son nom)
                $img = new Images();
                $img->setName($fichier);
                $article->addImage($img);
            }


            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('articles_index');
        }

        return $this->render('articles/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'articles_delete', methods: ['DELETE'])]
    public function delete(Request $request, Articles $article): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($article);
            $entityManager->flush();
        }

        return $this->redirectToRoute('articles_index');
    }


    /**
     * @Route("/supprime/image/{id}", name="articles_delete_image", methods={"DELETE"})
     */
    public function deleteImage(Images $image, Request $request){
        $data = json_decode($request->getContent(), true);
        
        //on verifie si le token est valide
        if ($this->isCsrfTokenValid('delete'.$image->getId(), $data['_token'])) {
            //on recupere le nom de l'image
            $nom = $image->getName();
            //on supprime le fichier
            unlink($this->getParameter('images_directory').'/'.$nom);

            //on supprile l'entree de la base
            $em = $this->getDoctrine()->getManager();
            $em ->remove($image);
            $em->flush();

            //on reponds en json
            return new JsonResponse(['success'=>1]);
        }else {
            return new JsonResponse(['error'=>'Token Invalide'], 400);
        }
    }
}
