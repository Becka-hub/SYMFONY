<?php

namespace App\Controller\Api\Secure;

use App\Entity\Comment;
use App\Service\Service;
use App\Shared\Messages;
use App\Shared\Reponses;
use App\Repository\CarRepository;
use App\Repository\UserRepository;
use App\Repository\CommentRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/comment', name: 'api_comment_ctrl')]
#[Security("is_granted('ROLE_USER')")]
class CommentController extends AbstractController
{
    private CommentRepository $commentRepository;
    private CarRepository $carRepository;
    private UserRepository $userRepository;
    private Reponses $reponse;
    private Service $service;

    public function __construct(CommentRepository $commentRepository, CarRepository $carRepository, UserRepository $userRepository, Reponses $reponse, Service $service)
    {
        $this->commentRepository = $commentRepository;
        $this->carRepository = $carRepository;
        $this->userRepository = $userRepository;
        $this->reponse = $reponse;
        $this->service = $service;
    }

    #[Route('/commentList', name: 'commentList', methods: 'GET')]
    public function commentList(): Response
    {
        $comments = $this->commentRepository->findAll();
        return $this->reponse->success(array_map(function (Comment $comment) {
            return $comment->tojson();
        }, $comments));
    }

    #[Route('/commentSingle/{id}', name: 'commentSingle', methods: 'GET')]
    public function commentSingle($id): Response
    {
        $comment = $this->commentRepository->findOneBy(['id' => $id]);
        if ($comment == "") {
            return $this->reponse->error(Messages::COMMENT_NOT_FOUND);
        } else {
            return $this->reponse->success(
                $comment->tojson()
            );
        }
    }

    #[Route('/commentSave', name: 'commentSave', methods: 'POST')]
    public function commentSave(): Response
    {
        $data = $this->service->json_decode();
        if (!isset($data->comment, $data->fk_car, $data->fk_user) || ($data->comment === "" || $data->fk_car === "" || $data->fk_user === "")) {
            return $this->reponse->error(Messages::FORM_INVALID);
        }
        $car = $this->carRepository->findOneBy(['id' => $data->fk_car]);
        if ($car=="") {
            return $this->reponse->error(Messages::CAR_NOT_FOUND);
        }
        $user = $this->userRepository->findOneBy(['id' => $data->fk_user]);
        if ($user=="") {
            return $this->reponse->error(Messages::USER_NOT_FOUND);
        }

        $comment = (new Comment())
            ->setComment($data->comment)
            ->setFkCar($car)
            ->setFkUser($user);
        $this->service->em()->persist($comment);
        $this->service->em()->flush();
        return $this->reponse->success($comment->tojson());
    }

    #[Route('/commentDelete/{id}', name: 'commentDelete', methods: 'DELETE')]
    public function delete(int $id): Response
    {
        $comment = $this->commentRepository->findOneBy(['id' => $id]);
        if (!$comment) return $this->reponse->error(Messages::COMMENT_NOT_FOUND);

        $this->service->em()->remove($comment);
        $this->service->em()->flush();
        return $this->reponse->success("Deleted");
    }
}
