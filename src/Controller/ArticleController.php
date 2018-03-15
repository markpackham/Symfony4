<?php

namespace App\Controller;

use App\Entity\Article;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ArticleController extends Controller
{
    /**
     * @Route("/")
     * @Method({"GET"})
     */
    public function index()
    {
        $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();
        // return new Response("<h1>Hello world</h1>");
        return $this->render('articles/index.html.twig', array('articles' => $articles));
    }


//    /**
//     * @Route("/article/save")
//     */
    /*
    public function save()
    {
        $entityManager = $this->getDoctrine()->getManager();

        $article = new Article();
        $article->setTitle('Article Two');
        $article->setBody('This is the body for article two');

        //take the data here, and later we flush it into the database
        $entityManager->persist($article);
        $entityManager->flush();

        return new Response('Saved an article with the id of ' .
            $article->getId());
        */
    
}