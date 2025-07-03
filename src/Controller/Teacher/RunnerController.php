<?php

namespace App\Controller\Teacher;

use App\Entity\Runners;
use App\Form\RunnnerAddForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/teacher/courses/{Id}/runner')]
final class RunnerController extends AbstractController
{
    #[Route('/add', name: 'runner_add')]
    public function index(Request $request, EntityManagerInterface $em, int $Id): Response
    {
        $runner = new Runners();
        $form = $this->createForm(RunnnerAddForm::class, $runner);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $course = $em->getRepository(\App\Entity\Courses::class)->find($Id);
            if ($course) {
                $runner->setCourse($course);
            }

            if ($runner->isTeacher()) {
                $teacher = $this->getUser();
                $runner->setTeacherId($teacher);
            }

            $em->persist($runner);
            $em->flush();

            $this->addFlash('success', 'Runner ajouté avec succès !');
            return $this->redirectToRoute('teacher_courses', ['id' => $Id]);
        }

        return $this->render('teacher/runner/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/details/{id}', name: 'runner_details')]
    public function details(int $id, EntityManagerInterface $em, int $Id): Response
    {
        $runner = $em->getRepository(Runners::class)->find($id);
        if (!$runner) {
            throw $this->createNotFoundException('Runner not found');
        }

        $logs = $em->getRepository(\App\Entity\LogScan::class)->findBy(['runner' => $id]);

        return $this->render('teacher/runner/details.html.twig', [
            'runner' => $runner,
            'logs' => $logs,
            'courseId' => $Id,
        ]);
    }
}
