<?php

namespace App\Controller\Api\V1\Full;

use App\Shared\Messages;
use App\Shared\Reponses;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/full/article', name: 'full_article_ctrl')]
class ArticleController extends AbstractController
{
    private ArticleRepository $articleRepository;
    private Reponses $reponse;

    public function __construct(ArticleRepository $articleRepository, Reponses $reponse)
    {
        $this->articleRepository = $articleRepository;
        $this->reponse = $reponse;
    }

    #[Route('/list', name: 'list')]
    public function list(): Response
    {
        $article = $this->articleRepository->findBy(['active' => true]);
        return $this->reponse->success($article);
    }


    #[Route('/findbyid/{id}', name: 'findbyid')]
    public function find_by_id(int $id): Response
    {
        return !$id ?
            $this->reponse->error(Messages::ARTICLE_NOT_FOUND) :
            $this->reponse->success(
                $this->articleRepository->findOneBy(['id' => $id,'active' => true])
            );
    }
}
