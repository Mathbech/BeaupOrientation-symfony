<?php

namespace App\Controller\Teacher;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Courses;
use App\Entity\Parcours;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/teacher')]

class TeacherController extends AbstractController
{
    #[Route('/', name: 'teacher_home')]
    public function index(EntityManagerInterface $em): Response
    {
        $coursesRepository = $em->getRepository(Courses::class);
        $parcoursRepository = $em->getRepository(Parcours::class);

        $coursesData = $coursesRepository->findBy(['user' => $this->getUser()]);
        $parcoursData = $parcoursRepository->findBy(['user' => $this->getUser()]);
        dump($coursesData);
        dump($parcoursData);
        return $this->render('teacher/home.html.twig', [
            'coursesData' => $coursesData,
            'parcoursData' => $parcoursData,
        ]);
    }
}
