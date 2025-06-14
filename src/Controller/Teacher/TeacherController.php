<?php

namespace App\Controller\Teacher;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Courses;
use App\Entity\Markers;
use App\Form\CourseAddFormType;
use Symfony\Bundle\SecurityBundle\Security;
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

        $coursesData = $coursesRepository->getCoursesByUser($this->user, null);
        // $parcoursData = $parcoursRepository->findBy(['user' => $this->getUser()]);
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

        // Transformation des données pour la vue
        if (!empty($coursesData)) {
            $coursesData = $coursesData[0]; // On prend uniquement la première entrée
            $coursesData['runners'] = !empty($coursesData['runners']) ? explode(',', $coursesData['runners']) : [];
            if (!empty($coursesData['runners'])) {
                $coursesData['runners'] = array_map(function ($runner) {
                    // On récupère les champs séparés par ":"
                    [$name, $code, $isTeacher] = array_pad(explode(':', $runner), 3, null);
                    return [
                        'name' => $name,
                        'code' => $code,
                        'isTeacher' => $isTeacher,
                    ];
                }, $coursesData['runners']);
            } else {
                $coursesData['runners'] = [];
            }

            // Combine points and QR codes into a single structure
            if (!empty($coursesData['point']) && !empty($coursesData['qrCode'])) {
                $points = array_map(function ($point) {
                    return explode(' : ', $point);
                }, explode(',', $coursesData['point']));

                $qrCodes = explode(',', $coursesData['qrCode']);
                $markerIds = explode(',', $coursesData['markersId']); // Ajoutez cette ligne pour récupérer les IDs des markers
                $markerNames = explode(',', $coursesData['markerName']); // Récupérer les noms des markers
                $markerTypes = explode(',', $coursesData['markerType']); // Récupérer les types des markers

                // Associer chaque point, QR code et ID
                $coursesData['markers'] = array_map(function ($point, $qrCode, $id, $name, $type) {
                    return [
                        'id' => $id,
                        'latitude' => $point[0],
                        'longitude' => $point[1],
                        'qrCode' => $qrCode,
                        'name' => $name,   // <-- ici
                        'type' => $type,   // <-- ici
                    ];
                }, $points, $qrCodes, $markerIds, $markerNames, $markerTypes);
            } else {
                $coursesData['markers'] = [];
            }

            unset($coursesData['point'], $coursesData['qrCode']); // Supprime les clés inutiles
        }

        if (!empty($coursesData['markersData'])) {
            $coursesData['markers'] = array_map(function ($marker) {
                // On récupère les 6 champs
                [$id, $latitude, $longitude, $qrCode, $type, $name] = explode(':', $marker);
                return [
                    'id' => $id,
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'qrCode' => $qrCode,
                    'type' => $type,
                    'name' => $name,
                ];
            }, explode(',', $coursesData['markersData']));
        } else {
            $coursesData['markers'] = [];
        }

        unset($coursesData['markersData']); // Supprime la clé brute

        return $this->render('teacher/courses.html.twig', [
            'coursesData' => $coursesData,
            'markerTypes' => Markers::TYPES,
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
            $course->setUser($this->getUser());
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
