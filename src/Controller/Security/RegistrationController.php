<?php

namespace App\Controller\Security;

use App\Entity\User;
use App\Entity\Schools;
use App\Form\RegistrationFormType;
use App\Form\SchoolFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register_step_1')]
    public function registerStep1(Request $request): Response
    {
        $school = new Schools();
        $form = $this->createForm(SchoolFormType::class, $school);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $session = $request->getSession();
            $session->set('school', $school);

            return $this->redirectToRoute('app_register_step_2');
        }

        return $this->render('Security/registration/register_step_1.html.twig', [
            'registrationFormStep1' => $form,
        ]);
    }


    #[Route('/register/your-credentials', name: 'app_register_step_2')]
    public function register_step_2(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $session = $request->getSession();
        $school = $session->get('school');

        if (!$school) {
            return $this->redirectToRoute('app_register_step_1');
        }

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();

            // encode the plain password
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

            $user->setSchools($school);

            // Persist both the user and the school
            $entityManager->persist($school);
            $entityManager->persist($user);
            $entityManager->flush();

            // Clear the session
            $session->remove('school');

            // Redirect to the admin page or any other page
            return $this->redirectToRoute('teacher_dashboard');
        }

        return $this->render('Security/registration/register_step_2.html.twig', [
            'registrationForm' => $form,
        ]);
    }
}
