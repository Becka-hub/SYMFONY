<?php

namespace App\Controller\Api\V1\Full;

use App\Shared\Messages;
use App\Shared\Reponses;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
#[Route('/full/category', name: 'full_category_ctrl')]
class CategoryController extends AbstractController
{
    private CategoryRepository $categoryRepository;
    private Reponses $reponse;

    public function __construct(CategoryRepository $categoryRepository, Reponses $reponse)
    {
        $this->categoryRepository = $categoryRepository;
        $this->reponse = $reponse;
    }

    #[Route('/list', name: 'list')]
    public function list(): Response
    {
        $category = $this->categoryRepository->findBy(['active' => true]);
        return $this->reponse->success($category);
    }

    #[Route('/list/article', name: 'list_article')]
    public function list_article(): Response
    {
        $article = $this->categoryRepository->findBy(['active' => true]);
        return $this->reponse->success($article);
    }

    #[Route('/findbyid/{id}', name: 'findbyid')]
    public function find_by_id(int $id): Response
    {
        return !$id ?
            $this->reponse->error(Messages::CATEGORY_NOT_FOUND) :
            $this->reponse->success(
                $this->categoryRepository->findOneBy(['id' => $id,'active' => true])
            );
    }



 
}