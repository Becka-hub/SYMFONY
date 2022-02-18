<?php

namespace App\Controller\Api\V1\Secure;

use App\Shared\Globals;
use App\Entity\Category;
use App\Shared\Messages;
use App\Shared\Reponses;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/secure/category', name: 'secure_category_ctrl')]
class CategoryController extends AbstractController
{
    private CategoryRepository $categoryRepository;
    private Reponses $reponse;
    private Globals $globals;


    public function __construct(CategoryRepository $categoryRepository, Reponses $reponse,Globals $globals)
    {
        $this->categoryRepository = $categoryRepository;
        $this->reponse = $reponse;
        $this->globals=$globals;
    }

    #[Route('/list/active', name: 'list_active')]
    public function list_active(): Response
    {
        $category = $this->categoryRepository->findBy(['active' => true]);
        return $this->reponse->success($category);
    }

    #[Route('/list/delete', name: 'list_delete')]
    public function list_delete(): Response
    {
        $category = $this->categoryRepository->findBy(['active' => false]);
        return $this->reponse->success($category);
    }

    #[Route('/list/active/article', name: 'list_active_article')]
    public function list_active_article(): Response
    {
        $article = $this->categoryRepository->findBy(['active' => true]);
        return $this->reponse->success($article);
    }

    #[Route('/list/delete/article', name: 'list_delete_article')]
    public function list_delete_article(): Response
    {
        $article = $this->categoryRepository->findBy(['active' => false]);
        return $this->reponse->success($article);
    }

    #[Route('/findbyid/{id}', name: 'findbyid')]
    public function find_by_id(int $id): Response
    {
        return !$id ?
            $this->reponse->error(Messages::CATEGORY_NOT_FOUND) :
            $this->reponse->success(
                $this->categoryRepository->findOneBy(['id' => $id])
            );
    }

    #[Route('/save', name: 'save')]
    public function save(): Response
    {
        $data=$this->globals->json_decode();
        if(!isset($data->name)){
            return $this->reponse->error(Messages::FORM_INVALID);
        }
        $category=(new Category())->setActive(true)
        ->setName($data->name)
        ->setDateSave(new \DateTime());
        $this->getDoctrine()->getManager()->persist($category);
        $this->getDoctrine()->getManager()->flush();
        return $this->reponse->success($category);
    }

    #[Route('/update', name: 'update')]
    public function update(): Response
    {
        $data=$this->globals->json_decode();
        if(!isset($data->id,$data->name)){
            return $this->reponse->error(Messages::FORM_INVALID);
        }
        $category=$this->categoryRepository->findOneBy(['id'=>$data->id,'active'=>true]);
        if(!$category){
            return $this->reponse->error(Messages::CATEGORY_NOT_FOUND);
        }

        $category->setActive(true)
        ->setName($data->name)
        ->setDateUpdated(new \DateTime());
        $this->getDoctrine()->getManager()->persist($category);
        $this->getDoctrine()->getManager()->flush();
        return $this->reponse->success($category);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(int $id): Response
    {
        if (!$id) return $this->reponse->error(Messages::CATEGORY_NOT_FOUND);
        $category = $this->categoryRepository->findOneBy(['id' => $id, 'active' => true]);
        if(!$category) return $this->reponse->error(Messages::CATEGORY_NOT_FOUND);

        $this->getDoctrine()->getManager()->remove($category);
        $this->getDoctrine()->getManager()->flush();
        return $this->reponse->success("Deleted");
    }
}
