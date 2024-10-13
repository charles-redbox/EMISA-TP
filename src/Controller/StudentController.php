<?php

namespace App\Controller;

use App\Entity\Student;
use App\Form\StudentType;
use App\Repository\StudentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/')]
class StudentController extends AbstractController
{
    #[Route('/admin/students', name: 'app_student_index', methods: ['GET'])]
    public function index(StudentRepository $studentRepository): Response
    {
        return $this->render('student/index.html.twig', [
            'students' => $studentRepository->findAll(),
        ]);
    }

    #[Route('/admin/students/new', name: 'app_student_new', methods: ['GET', 'POST'])]
    public function new(Request $request, StudentRepository $studentRepository): Response
    {
        $student = new Student();
        $form = $this->createForm(StudentType::class, $student);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion de l'upload de la photo
            $photoFile = $form->get('photo')->getData();
            if ($photoFile) {
                // Générer un nom unique pour le fichier
                $newFilename = uniqid() . '.' . $photoFile->guessExtension();

                // Déplacer le fichier dans le répertoire configuré
                $photoFile->move(
                    $this->getParameter('photos_directory'),
                    $newFilename
                );

                // Sauvegarder le nom du fichier dans l'entité `Student`
                $student->setPhoto($newFilename);
            }

            // Enregistrer l'étudiant avec le StudentRepository
            $studentRepository->add($student, true);

            return $this->redirectToRoute('app_student_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('student/new.html.twig', [
            'student' => $student,
            'form' => $form,
        ]);
    }

    #[Route('/students/search/firstname/{firstname}', name: 'student_search_by_firstname', methods: ['GET'])]
    public function searchByFirstName(StudentRepository $studentRepository, string $firstname): Response
    {
        // Rechercher les étudiants dont le prénom contient "firstname"
        $students = $studentRepository->findStudentsByFirstName($firstname);

        return $this->render('student/search.html.twig', [
            'students' => $students,
        ]);
    }

    #[Route('/students/search/email/{email}', name: 'student_search_by_email', methods: ['GET'])]
    public function searchByEmail(StudentRepository $studentRepository, string $email): Response
    {
        // Rechercher les étudiants dont l'email contient "email"
        $students = $studentRepository->findStudentsByEmail($email);

        return $this->render('student/search.html.twig', [
            'students' => $students,
        ]);
    }

    #[Route('/students/{id}', name: 'app_student_show', methods: ['GET'])]
    public function show(Student $student): Response
    {
        return $this->render('student/show.html.twig', [
            'student' => $student,
        ]);
    }

    #[Route('/admin/students/{id}/edit', name: 'app_student_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Student $student, StudentRepository $studentRepository): Response
    {
        $form = $this->createForm(StudentType::class, $student);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Gestion de l'upload de la photo
            $photoFile = $form->get('photo')->getData();
            if ($photoFile) {
                // Générer un nom unique pour le fichier
                $newFilename = uniqid() . '.' . $photoFile->guessExtension();

                // Déplacer le fichier dans le répertoire configuré
                $photoFile->move(
                    $this->getParameter('photos_directory'),
                    $newFilename
                );

                // Sauvegarder le nom du fichier dans l'entité `Student`
                $student->setPhoto($newFilename);
            }

            $studentRepository->add($student, true);

            return $this->redirectToRoute('app_student_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('student/edit.html.twig', [
            'student' => $student,
            'form' => $form,
        ]);
    }

    #[Route('/admin/students/{id}', name: 'app_student_delete', methods: ['POST'])]
    public function delete(Request $request, Student $student, StudentRepository $studentRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$student->getId(), $request->request->get('_token'))) {
            $studentRepository->remove($student, true);
        }

        return $this->redirectToRoute('app_student_index', [], Response::HTTP_SEE_OTHER);
    }

}
