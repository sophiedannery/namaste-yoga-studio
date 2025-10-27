<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request, 
        UserPasswordHasherInterface $userPasswordHasher, 
        Security $security, 
        EntityManagerInterface $entityManager,
        LoggerInterface $logger
        ): Response
    {

        if ($security->getUser()) {
            return $this->redirectToRoute('app_profile');
        }

        $user = new User();

        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();

            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));
            $user->setRoles(['ROLE_USER']);
            $user->setEmail(mb_strtolower($user->getEmail() ?? ''));
            $user->setIsActive(true);

            $entityManager->persist($user);
            $entityManager->flush();

            $security->login($user, 'form_login', 'main');
            return $this->redirectToRoute('app_profile');
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $errors = [];

            foreach ($form->getErrors(true, false) as $error) {
                if ($error instanceof \Symfony\Component\Form\FormError) {
                    $errors[] = $error->getMessage();
                }
            }

            $logger->error('Inscription invalide', ['errors' => $errors]);
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }
}
