<?php

namespace App\Controller;

use App\Form\UserFormType;
use App\Repository\UserRepository;
use App\Form\ChangePasswordFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class AccountController extends AbstractController
{
    #[Route('/account', name: 'app_account')]
    public function index(): Response
    {
        $user = $this->getUser();
        if($user){
            $this->addFlash('success', 'Bienvenu sur votre profil');
        }else{
            $this->addFlash('danger', 'Connecté vous d\'abord avant d\'accéder au profil');
            return $this->redirectToRoute('app_login');
        }
        return $this->render('account/index.html.twig',['user' =>$user]);
    }

    #[Route('/account/edit', name:'app_account_edit', methods:['GET','POST'])]
    public function edit(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        if (!$user) {
            $this->addFlash('danger', 'Connecté vous d\'abord avant de modifier votre profil');
            return $this->redirectToRoute('app_login');
        }
        $form = $this->createForm(UserFormType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('app_account');
        }

        return $this->renderForm('account/edit.html.twig',[
            'formEditAccount' => $form
        ]);
    }

    #[Route('/account/password-change', name: 'app_account_password_change', methods:['GET', 'POST'])]
    public function changePassword(Request $request, 
    UserPasswordHasherInterface $userPasswordHasher,
    UserRepository $userRepository
    ): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(ChangePasswordFormType::class, null, [
            'current_password_required' => true
        ]);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            $user->setPassword(
                $userPasswordHasher->hashPassword($user, $form->get('plainPassword')->getData())
            );
            $userRepository->add($user, true);

             return $this->redirectToRoute('app_account');
        }
        return $this->render('account/change_password.html.twig',[
            'formChangePassword' => $form->createView()
        ]);
    }
}
