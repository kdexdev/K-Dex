<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\LoginAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        UserAuthenticatorInterface $userAuthenticator,
        LoginAuthenticator $authenticator,
        EntityManagerInterface $entityManager
    ): Response {
        $user = new User();

        $registrationForm = $this->createForm(
            type: RegistrationFormType::class,
            data: $user
        );
        $registrationForm->handleRequest($request);

        // Initializing user detail variables
        $userEmail = '';
        $userUsername = '';

        if ($registrationForm->isSubmitted()) {
            $userEmail      = $registrationForm->get("email")->getData();
            $userUsername   = $registrationForm->get("username")->getData();

            if ($registrationForm->isValid()) {
                $user->setEmail($userEmail);
                $user->setUsername($userUsername);
                // encode the plain password
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $registrationForm->get("password")->getData()
                    )
                );

                // Update the database entry
                $entityManager->persist($user);
                $entityManager->flush();

                return $userAuthenticator->authenticateUser(
                    $user,
                    $authenticator,
                    $request
                );
            }
        }

        // There were issues with the registration form, reload the page
        return $this->render('registration/register.html.twig', [
            'controller_name'   => 'AuthentificationController',
            'formRegister'      => $registrationForm->createView(),
            // 'errorRegistration' => $authError,
            'emailLast'         => $userEmail,
            'usernameLast'      => $userUsername
        ]);
    }
}
