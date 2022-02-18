<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AccueilController extends AbstractController
{
    private $taskRepository;
    private $flashMessage;

    public function __construct(TaskRepository $taskRepository, FlashBagInterface $flashMessage)
    {
        $this->taskRepository = $taskRepository;
        $this->flashMessage = $flashMessage;
    }



    #[Route('/', name: 'accueil')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('accueil/index.html.twig', [
            'controller_name' => 'Accueil',
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    #[Route('/home', name: 'app_home')]
    public function home(): Response
    {
        $data = $this->taskRepository->findAll();
        return $this->render('accueil/home.html.twig', [
            'controller_name' => 'home',
            'task' => $data
        ]);
    }



    #[Route('/create/task', name: 'task_create')]
    public function createTask(Request $request): Response
    {
        $task = new Task();

        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user=$this->getUser();
            $task->setAuthor($user);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($task);
            $entityManager->flush();
            $this->flashMessage->add("success", "Note ajoutée avec success");
            return $this->redirectToRoute('task_create');
        }

        return $this->render('accueil/add.html.twig', ['controller_name' => 'create_task', 'form' => $form->createView()]);
    }


    #[Route('/task/{id}', name: 'task_show')]
    public function showTask($id): response
    {
        $task = $this->taskRepository->find($id);

        if (!$task) {
            return $this->redirectToRoute('task');
        }

        // dd($task);
        return $this->render('accueil/show.html.twig', [
            'controller_name' => 'Task_show',
            'task' => $task
        ]);
    }




    #[Route('/edit/task/{id}', name: 'task_edit')]
    public function editTask(Request $request, $id): Response
    {
        $task = $this->taskRepository->find($id);
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($task);
            $entityManager->flush();
            $this->flashMessage->add("success", "Note modifiée avec success");
            return $this->redirectToRoute('app_home');
        }

        return $this->render('accueil/edit.html.twig', ['controller_name' => 'edit_task', 'form' => $form->createView()]);
    }






    #[Route('/delete/task/{id}', name: 'task_delete')]
    public function deleteTask($id): response
    {
        $task = $this->taskRepository->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($task);
        $entityManager->flush();
        $this->flashMessage->add("success", "Note suprimée avec success");
        return $this->redirectToRoute('app_home');
    }




    #[Route('/profile', name: 'app_profile')]
    public function profile(): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('accueil');
        }
        return $this->render('accueil/profile.html.twig', [
            'controller_name' => 'profile',
            'user'=>$this->getUser()
        ]);
    }


    
    #[Route('/update/image', name: 'app_uploadImage')]
    public function updateProfileImage(Request $request): Response
    {
      if($request->files->get('image')!=""){
        $user = $this->getUser();
        $image = $request->files->get('image');
        $extension = $image->getClientOriginalExtension();
        $image_name = time().'.'.$extension;
        
        try {
            $image->move($this->getParameter('brochures_directory'),$image_name);
        } catch (FileException $e) {
            
        }
        $user->setImage($image_name);
        $entityManager=$this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();
        $this->flashMessage->add("success", "Profile modifié");

       return $this->redirectToRoute('app_profile');
      }else{
        return $this->redirectToRoute('app_profile');
      }
    }
 



    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
