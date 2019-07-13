<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Todo;
//use Doctrine\DBAL\Types\TextType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class TodoController extends Controller
{
    /**
     * @Route("/todo", name="todo_list")
     */
    public function listAction()
    {
        $todos = $this->getDoctrine()
            ->getRepository('AppBundle:Todo')
            ->findAll();
        // replace this example code with whatever you need
        return $this->render('todo/index.html.twig', array(
                'todos' => $todos
            )
        );
    }

    /**
     * @Route("/todo/create", name="create")
     */
    public function createAction(Request $request)
    {
        $todo = new Todo;

        $form = $this->createFormBuilder($todo)
            ->add('name', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'style' => 'margin-bottom:15px'
                )
            ))
            ->add('category', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'style' => 'margin-bottom:15px'
                )
            ))
            ->add('description', TextareaType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'style' => 'margin-bottom:15px'
                )
            ))
            ->add('priority', ChoiceType::class, array(
                'choices' => array('Very Low' => 'Very Low', 'Low' => 'Low', 'Normal' => 'Normal', 'High' => 'High'),
                'attr' => array(
                    'class' => 'form-control',
                    'style' => 'margin-bottom:15px'
                )
            ))
            ->add('due_date', DateTimeType::class, array(
                'attr' => array(
                    'class' => 'formcontrol',
                    'style' => 'margin-bottom:15px'
                )
            ))
            ->add('save', SubmitType::class, array(
                'attr' => array(
                    'label' => 'Create Todo',
                    'class' => 'btn btn-primary',
                    'style' => 'margin-bottom:15px'
                )
            ))
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //Get data from form
            $name = $form['name']->getData();
            $category = $form['category']->getData();
            $description = $form['description']->getData();
            $priority = $form['priority']->getData();
            $due_date = $form['due_date']->getData();
            $now = new\DateTime('now');

            //Setters

            $todo->setName($name);
            $todo->setCategory($category);
            $todo->setDescription($description);
            $todo->setPriority($priority);
            $todo->setDueDate($due_date);
            $todo->setCreateDate($now);

            $em = $this->getDoctrine()->getManager();

            $em->persist($todo);
            $em->flush();

            //Send a message
            $this->addFlash(
                'notice',
                'Todo Added'
            );

            return $this->redirectToRoute('todo_list');
        }

        // replace this example code with whatever you need
        return $this->render('todo/create.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/todo/edit/{id}", name="edit")
     */
    public function editAction($id, Request $request)
    {
        $todo = $this->getDoctrine()
            ->getRepository('AppBundle:Todo')
            ->find($id);

        //Get current data
        $todo->setName($todo->getName());
        $todo->setCategory($todo->getCategory());
        $todo->setDescription($todo->getDescription());
        $todo->setPriority($todo->getPriority());
        $todo->setDueDate($todo->getDueDate());
        $now = new\DateTime('now');
        $todo->setCreateDate($now);

        $form = $this->createFormBuilder($todo)
            ->add('name', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'style' => 'margin-bottom:15px'
                )
            ))
            ->add('category', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'style' => 'margin-bottom:15px'
                )
            ))
            ->add('description', TextareaType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'style' => 'margin-bottom:15px'
                )
            ))
            ->add('priority', ChoiceType::class, array(
                'choices' => array('Very Low' => 'Very Low', 'Low' => 'Low', 'Normal' => 'Normal', 'High' => 'High'),
                'attr' => array(
                    'class' => 'form-control',
                    'style' => 'margin-bottom:15px'
                )
            ))
            ->add('due_date', DateTimeType::class, array(
                'attr' => array(
                    'class' => 'formcontrol',
                    'style' => 'margin-bottom:15px'
                )
            ))
            ->add('save', SubmitType::class, array(
                'attr' => array(
                    'label' => 'Update Todo',
                    'class' => 'btn btn-primary',
                    'style' => 'margin-bottom:15px'
                )
            ))
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //Get data from form
            $name = $form['name']->getData();
            $category = $form['category']->getData();
            $description = $form['description']->getData();
            $priority = $form['priority']->getData();
            $due_date = $form['due_date']->getData();
            $now = new\DateTime('now');

            $em = $this->getDoctrine()->getManager();
            $todo = $em->getRepository('AppBundle:Todo')->find($id);

            //Setters, change data
            $todo->setName($name);
            $todo->setCategory($category);
            $todo->setDescription($description);
            $todo->setPriority($priority);
            $todo->setDueDate($due_date);
            $todo->setCreateDate($now);

            $em->flush();

            //Send a message
            $this->addFlash(
                'notice',
                'Todo Updated'
            );

            return $this->redirectToRoute('todo_list');
        }

        // replace this example code with whatever you need
        return $this->render('todo/edit.html.twig', array(
                'todo' => $todo,
                'form' => $form->createView()
            )
        );
    }

    /**
     * @Route("/todo/details/{id}", name="details")
     */
    public function detailsAction($id)
    {
        $todo = $this->getDoctrine()
            ->getRepository('AppBundle:Todo')
            ->find($id);
        // replace this example code with whatever you need
        return $this->render('todo/details.html.twig', array(
                'todo' => $todo
            )
        );
    }

    /**
     * @Route("/todo/delete/{id}", name="todo_delete")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $todo = $em->getRepository('AppBundle:Todo')->find($id);

        $em->remove($todo);
        $em->flush();

        //Send a message
        $this->addFlash(
            'notice',
            'Todo Deleted'
        );

        return $this->redirectToRoute('todo_list');

    }
}
