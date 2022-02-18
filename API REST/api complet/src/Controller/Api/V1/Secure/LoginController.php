<?php

namespace App\Controller\Api\V1\Secure;

use App\Shared\Messages;
use App\Shared\Reponses;
use App\Repository\LoginRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
#[Route('/secure/login', name: 'secure_login_ctrl')]
class LoginController extends AbstractController
{
    private LoginRepository $loginRepository;
    private Reponses $reponse;

    public function __construct(LoginRepository $loginRepository, Reponses $reponse)
    {
        $this->loginRepository = $loginRepository;
        $this->reponse = $reponse;
    }

    #[Route('/list/active', name: 'list_active')]
    public function list_active(): Response
    {
        $login = $this->loginRepository->findBy(['active' => true]);
        return $this->reponse->success($login);
    }

    #[Route('/list/delete', name: 'list_delete')]
    public function list_delete(): Response
    {
        $login = $this->loginRepository->findBy(['active' => false]);
        return $this->reponse->success($login);
    }
    #[Route('/list/active/login', name: 'list_active_login')]
    public function list_active_article(): Response
    {
        $article = $this->loginRepository->findBy(['active' => true]);
        return $this->reponse->success($article);
    }

    #[Route('/list/delete/login', name: 'list_delete_login')]
    public function list_delete_article(): Response
    {
        $article = $this->loginRepository->findBy(['active' => false]);
        return $this->reponse->success($article);
    }

    #[Route('/findbyid/{id}', name: 'findbyid')]
    public function find_by_id(int $id): Response
    {
        return !$id ?
            $this->reponse->error(Messages::LOGIN_NOT_FOUND) :
            $this->reponse->success(
                $this->loginRepository->findOneBy(['id' => $id])
            );
    }

    #[Route('/save', name: 'save')]
    public function save(): Response
    {

    }

    #[Route('/update/id', name: 'update')]
    public function update($id): Response
    {

    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(int $id): Response
    {
        if (!$id) return $this->reponse->error(Messages::LOGIN_NOT_FOUND);
        $login = $this->loginRepository->findOneBy(['id' => $id, 'active' => true]);
        if(!$login) return $this->reponse->error(Messages::LOGIN_NOT_FOUND);

        $this->getDoctrine()->getManager()->remove($login);
        $this->getDoctrine()->getManager()->flush();
        return $this->reponse->success("Deleted");
    } 
}