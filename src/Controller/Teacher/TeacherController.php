<?php

namespace App\Controller\Teacher;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Courses;
// use App\Entity\Parcours;
use App\Form\CourseAddFormType;
use Symfony\Bundle\SecurityBundle\Security;
// use App\Form\ParcoursAddFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/teacher')]

class TeacherController extends AbstractController
{
    private $user;
    public function __construct(Security $security)
    {
        $this->user = $security->getUser()->getId();
    }
    #[Route('/', name: 'teacher_home')]
    public function index(EntityManagerInterface $em): Response
    {
        $coursesData = [];
        $coursesRepository = $em->getRepository(Courses::class);
        // $parcoursRepository = $em->getRepository(Parcours::class);

        $coursesData = $coursesRepository->getCoursesByUser($this->user, 0);
        // $parcoursData = $parcoursRepository->findBy(['user' => $this->getUser()]);
        dump($coursesData);
        return $this->render('teacher/home.html.twig', [
            'coursesData' => $coursesData,
            // 'parcoursData' => $parcoursData,
        ]);
    }

    #[Route('/courses/{id}', name: 'teacher_courses')]
    public function courses(EntityManagerInterface $em, $id): Response
    {
        $coursesRepository = $em->getRepository(Courses::class);
        $coursesData = $coursesRepository->getCoursesByUser($this->user, $id);
        return $this->render('teacher/courses.html.twig', [
            'coursesData' => $coursesData,
        ]);
    }

    // #[Route('/parcours', name: 'teacher_parcours')]
    // public function parcours(EntityManagerInterface $em): Response
    // {
    //     $parcoursRepository = $em->getRepository(Parcours::class);
    //     $parcoursData = $parcoursRepository->findBy(['user' => $this->getUser()]);
    //     return $this->render('teacher/parcours.html.twig', [
    //         'parcoursData' => $parcoursData,
    //     ]);
    // }

    #[Route('/course/add', name: 'teacher_course_add')]
    public function courseAdd(EntityManagerInterface $em, Request $request): Response
    {
        $newCourse = new Courses();
        $newCourse->setUser($this->getUser());

        $forms = $this->createForm(CourseAddFormType::class, $newCourse, [
            'user' => $this->user
        ]);
        $forms->handleRequest($request);

        if ($forms->isSubmitted() && $forms->isValid()) {
            $course = $forms->getData();
            $course->setUser($this->user);
            $em->persist($course);
            $em->flush();
            return $this->redirectToRoute('teacher_home');
        }

        return $this->render('teacher/courseAdd.html.twig', [
            'form' => $forms->createView(),
        ]);
    }

    // #[Route('/parcours/add', name: 'teacher_parcours_add')]
    // public function parcoursAdd(EntityManagerInterface $em, Request $request): Response
    // {
    //     $newParcours = new Courses();

    //     $forms = $this->createForm(ParcoursAddFormType::class);
    //     $forms->handleRequest($request);

    //     if ($forms->isSubmitted() && $forms->isValid()) {
    //         $parcours = $forms->getData();
    //         $parcours->setUser($this->getUser());
    //         $parcours->setCreatedAt(new \DateTime());
    //         $parcours->setUpdatedAt(new \DateTime());
    //         $parcours->setActive(true);
    //         $em->persist($parcours);
    //         $em->flush();
    //         return $this->redirectToRoute('teacher_parcours');
    //     }

    //     return $this->render('teacher/parcoursAdd.html.twig', [
    //         'form' => $forms->createView(),
    //     ]);
    // }
}
