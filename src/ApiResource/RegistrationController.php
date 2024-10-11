<?php 

namespace App\ApiResource;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegistrationController extends AbstractController
{
    // La méthode __invoke doit être présente
    public function __invoke(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Créer un nouvel utilisateur
        $user = new User();
        $user->setUsername($data['username'] ?? null);
        $user->setEmail($data['email'] ?? null);

        // Valider l'utilisateur
        $errors = $validator->validate($user);
        if (count($errors) > 0) {
            return $this->json([
                'status' => 'error',
                'message' => (string) $errors,
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        // Hacher le mot de passe
        $hashedPassword = $userPasswordHasher->hashPassword($user, $data['password'] ?? '');
        $user->setPassword($hashedPassword);

        // Persister l'utilisateur
        $entityManager->persist($user);
        $entityManager->flush();

        // Retourner une réponse de succès
        return $this->json([
            'status' => 'success',
            'message' => 'User registered successfully!',
        ], JsonResponse::HTTP_CREATED);
    }
}
