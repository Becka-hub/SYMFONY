<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Entity\Task;
use App\Entity\Todo;
use App\Entity\Monde;
use App\Form\BlogType;
use App\Form\TaskType;
use App\Form\TodoType;
use App\Form\MondeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request, EntityManagerInterface $entityManager, FlashBagInterface $flashMessage): Response
    {
        $task = new Task();
        $todo = new Todo();
        $blog = new Blog();
        $monde = new Monde();
        $formTask = $this->createForm(TaskType::class, $task);
        $formTodo = $this->createForm(TodoType::class, $todo);
        $formBlog = $this->createForm(BlogType::class, $blog);
        $formMonde = $this->createForm(MondeType::class, $monde);

        $formTask->handleRequest($request);

        if ($formTask->isSubmitted() && $formTask->isValid()) {
            $task = $formTask->getData();
            $entityManager->persist($task);
            $entityManager->flush();
            $flashMessage->add("success", "Note ajoutée avec success");
            return $this->redirectToRoute('app_home');
        }

        return $this->render('home/index.html.twig', [
            'controller_name' => 'Home',
            'formTask' => $formTask->createView(),
            'formTodo' => $formTodo->createView(),
            'formBlog' => $formBlog->createView(),
            'formMonde' => $formMonde->createView(),
        ]);
    }

    #[Route('/todo', name: 'app_todo')]
    public function todo(Request $request, EntityManagerInterface $entityManager, FlashBagInterface $flashMessage)
    {
        $nom = $request->request->get('todo')['nom'];
        $email = $request->request->get('todo')['prenom'];
        $message = $request->request->get('todo')['description'];

        $todo = new Todo();
        $todo->setNom($nom);
        $todo->setPrenom($email);
        $todo->setDescription($message);
        $entityManager->persist($todo);
        $entityManager->flush();

        $flashMessage->add("success", "Todo ajoutée avec success");
        return $this->redirectToRoute('app_home');
    }

    #[Route('/blog', name: 'app_blog')]
    public function blog(Request $request, EntityManagerInterface $entityManager, FlashBagInterface $flashMessage, SluggerInterface $slugger)
    {
        $profile = $request->files->get('blog')['profile'];
        $file = $request->files->get('blog')['file'];

        if ($profile && $file) {

            $originalProfilename = pathinfo($profile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeProfilename = $slugger->slug($originalProfilename);
            $profileName = $safeProfilename . '-' . uniqid() . '.' . $profile->guessExtension();

            try {
                $profile->move(
                    $this->getParameter('brochures_directory'),
                    $profileName
                );
            } catch (FileException $e) {
            }




            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $fileName = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

            try {
                $file->move(
                    $this->getParameter('brochures_directory'),
                    $fileName
                );
            } catch (FileException $e) {
            }

            $blog = new blog();
            $blog->setProfile($profileName);
            $blog->setFile($fileName);
            $entityManager->persist($blog);
            $entityManager->flush();
        }
           $flashMessage->add("success", "blog ajoutée avec success");
           return $this->redirectToRoute('app_home');
    }

    #[Route('/monde', name: 'app_monde')]
    public function monde(Request $request, EntityManagerInterface $entityManager, FlashBagInterface $flashMessage)
    {
        $continent = $request->request->get('monde')['continent'];
        $pays = $request->request->get('monde')['pays'];
        $sexe = $request->request->get('monde')['sexe'];
        $ville = $request->request->get('monde')['ville'];
 
        $monde= new Monde();
        $monde->setContinent($continent);
        $monde->setPays([$pays]);
        $monde->setSexe($sexe);
        $monde->setVille($ville);

        $entityManager->persist($monde);
        $entityManager->flush();
        $flashMessage->add("success", "blog ajoutée avec success");
        return $this->redirectToRoute('app_home');
    }
}
