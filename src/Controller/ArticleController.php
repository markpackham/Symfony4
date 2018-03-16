<?php

namespace App\Controller;

use App\Entity\Article;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;


class ArticleController extends Controller {

  /**
   * @Route("/", name="article_list")
   * @Method({"GET"})
   */
  public function index() {
    $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();
    // return new Response("<h1>Hello world</h1>");
    return $this->render('articles/index.html.twig', ['articles' => $articles]);
  }

  /**
   * @Route("/article/new", name="new_article")
   * @Method({"GET","POST"})
   */
  public function new(Request $request) {
    $article = new Article();
    $form = $this->createFormBuilder($article)
      ->add('title', TextType::class, [
        'attr' =>
          ['class' => 'form-control'],
      ])
      ->add('body', TextareaType::class, [
        //body not require since we put required => FALSE
        'required' => FALSE,
        'attr' =>
          [
            'class' => 'form-control',
          ],
      ])
      //Save button
      ->add('save', SubmitType::class, [
        'label' => 'Create',
        'attr' =>
        //mt-3 means margin top 3
          [
            'class' => 'btn btn-primary mt-3',
          ],
      ])->getForm();

    $form->handleRequest($request);

    //check submission
    if ($form->isSubmitted() && $form->isValid()) {
      $article = $form->getData();

      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->persist($article);
      $entityManager->flush();

      //send us back to the homepage after flushing our persisted data
      return $this->redirectToRoute('article_list');
    }

    return $this->render('articles/new.html.twig', [
      'form' => $form->createView(),
    ]);
  }


  /**
   * @Route("/article/edit/{id}", name="edit_article")
   * @Method({"GET","POST"})
   */
  public function edit(Request $request, $id) {
    $article = new Article();
    $article = $this->getDoctrine()->getRepository(Article::class)->find($id);

    $form = $this->createFormBuilder($article)
      ->add('title', TextType::class, [
        'attr' =>
          ['class' => 'form-control'],
      ])
      ->add('body', TextareaType::class, [
        //body not require since we put required => FALSE
        'required' => FALSE,
        'attr' =>
          [
            'class' => 'form-control',
          ],
      ])
      //Save button
      ->add('save', SubmitType::class, [
        'label' => 'Update',
        'attr' =>
        //mt-3 means margin top 3
          [
            'class' => 'btn btn-primary mt-3',
          ],
      ])->getForm();

    $form->handleRequest($request);

    //check submission
    if ($form->isSubmitted() && $form->isValid()) {

      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->flush();

      //send us back to the homepage after flushing our persisted data
      return $this->redirectToRoute('article_list');
    }

    return $this->render('articles/edit.html.twig', [
      'form' => $form->createView(),
    ]);
  }


  //show needs to go below new_article or you end up with nulls like the message below
  // "Impossible to access an attribute ("title") on a null variable."
  /**
   * @Route("/article/{id}", name="article_show")
   */
  public function show($id) {
    $article = $this->getDoctrine()->getRepository(Article::class)->find($id);
    return $this->render('articles/show.html.twig', ['article' => $article]);
  }

  /**
   * @Route("/articles/delete/{id}")
   * @Method({"DELETE"})
   */
  public function delete(Request $request, $id) {
    $article = $this->getDoctrine()->getRepository(Article::class)->find($id);

    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->remove($article);
    $entityManager->flush();

    $response = new Response();
    $response->send();
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