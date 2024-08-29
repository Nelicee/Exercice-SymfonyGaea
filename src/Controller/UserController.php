<?php

namespace App\Controller;


use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;


class UserController extends AbstractController
{
    #[Route('/', name: 'user.index')]
    public function index(UserRepository $repository, UserService $userService): Response
    {
        $users = $repository->findAll();
        $age = [];
        foreach($users as $user){
            $age[$user->getId()] = $userService->AgeCalculation($user);
        }
            
    
        return $this->render('user/index.html.twig', [
            'users' => $users,
            'age' => $age
           
        ]);
    }   
    
    
    #[Route('/user/{id}', name: 'user.show', requirements: ['id' => Requirement::DIGITS])]
    public function show( UserRepository $repository, int $id ): Response
    {
       
         $user = $repository->find($id);
   
    
       
        return $this->render('user/show.html.twig', [
             'user' => $user,
        
             
        ]);
    }

    

    #[Route('/user/{id}/delete', name: "user.delete", methods: ['DELETE'], requirements: ['id' => Requirement::DIGITS])]
    public function delete (User $user, EntityManagerInterface $em){
     
        $em->remove($user);
        $em->flush();
       
        $this->addFlash('success', 'l\'utilisateur a bien été supprimé');
        
        return $this->redirectToRoute('user.index');

    }

    #[Route('/user/{id}/edit', name: "user.edit", methods: ['GET', 'POST'], requirements: ['id' => Requirement::DIGITS])]
    public function Edit (User $user, Request $request, EntityManagerInterface $em){
      $form = $this->createForm(UserType::class, $user);
      $form->handleRequest($request);
      if  ($form->isSubmitted() && $form->isValid()) {
        $em->flush();
        $this->addFlash('success', 'l\'utilisateur a bien été modifié');
        return $this->redirectToRoute('user.index');
      }
     
    
        return $this->render('user/edit.html.twig', [
            'form' => $form,
            'user' => $user
        ]);

    }




    #[Route('/user/create', name: "user.create")]
    public function create (Request $request, EntityManagerInterface $em){
        $user = new User;
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'l\'utilisateur a bien été créé');
            return $this->redirectToRoute('user.index');

        }
        return $this->render('user/create.html.twig', [
        'form' => $form
        ]);
    }





}