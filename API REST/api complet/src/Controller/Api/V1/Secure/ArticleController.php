<?php

namespace App\Controller\Api\V1\Secure;

use App\Entity\Article;
use App\Shared\Globals;
use App\Shared\Messages;
use App\Shared\Reponses;
use Symfony\Component\Mime\Message;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/secure/article', name: 'secure_article_ctrl')]
class ArticleController extends AbstractController
{
    private ArticleRepository $articleRepository;
    private Reponses $reponse;
    private Globals $globals;
    private CategoryRepository $categoryRepository;

    public function __construct(ArticleRepository $articleRepository,CategoryRepository $categoryRepository, Reponses $reponse, Globals $globals)
    {
        $this->articleRepository = $articleRepository;
        $this->reponse = $reponse;
        $this->globals = $globals;
        $this->categoryRepository = $categoryRepository;
    }

    #[Route('/list/active', name: 'list_active')]
    public function list_active(): Response
    {
        $article = $this->articleRepository->findBy(['active' => true]);
        return $this->reponse->success($article);
    }

    #[Route('/list/delete', name: 'list_delete')]
    public function list_delete(): Response
    {
        $article = $this->articleRepository->findBy(['active' => false]);
        return $this->reponse->success($article);
    }

    #[Route('/findbyid/{id}', name: 'findbyid')]
    public function find_by_id(int $id): Response
    {
        return !$id ?
            $this->reponse->error(Messages::ARTICLE_NOT_FOUND) :
            $this->reponse->success(
                $this->articleRepository->findOneBy(['id' => $id])
            );
    }

    #[Route('/save', name: 'save')]
    public function save(): Response
    {
        $data = $this->globals->json_decode();
        if (!isset($data->title, $data->content, $data->fk_categorie)) {
            return $this->reponse->error(Messages::FORM_INVALID);
        }
        $categorie = $this->categoryRepository->findOneBy(['id' => $data->fk_categorie, 'active' => true]);
        if (!$categorie) {
            return $this->reponse->error(Messages::CATEGORY_NOT_FOUND);
        }

        $article = (new Article())->setActive(true)
             ->setTitle($data->title)
             ->setContent($data->content)
             ->setDateSave(new \DateTime())
             ->setFkCategory($categorie);
        $this->getDoctrine()->getManager()->persist($article);
        $this->getDoctrine()->getManager()->flush();
        return $this->reponse->success($article);
    }

    #[Route('/update', name: 'update')]
    public function update(): Response
    {
        $data = $this->globals->json_decode();
        if (!isset($data->id, $data->title, $data->content, $data->fk_categorie)) {
            return $this->reponse->error(Messages::FORM_INVALID);
        }
        $categorie = $this->categoryRepository->findOneBy(['id' => $data->fk_categorie, 'active' => true]);
        if (!$categorie) {
            return $this->reponse->error(Messages::CATEGORY_NOT_FOUND);
        }

        $article = $this->articleRepository->findOneBy(['id' => $data->fk_categorie, 'active' => true]);
        if (!$article) {
            return $this->reponse->error(Messages::ARTICLE_NOT_FOUND);
        }

        $article->setActive(true)
             ->setTitle($data->title)
             ->setContent($data->content)
             ->setDateUpdated(new \DateTime())
             ->setFkCategory($categorie);
        $this->getDoctrine()->getManager()->persist($article);
        $this->getDoctrine()->getManager()->flush();
        return $this->reponse->success($article);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(int $id): Response
    {
        if (!$id) return $this->reponse->error(Messages::ARTICLE_NOT_FOUND);
        $article = $this->articleRepository->findOneBy(['id' => $id, 'active' => true]);
        if(!$article) return $this->reponse->error(Messages::ARTICLE_NOT_FOUND);

        $this->getDoctrine()->getManager()->remove($article);
        $this->getDoctrine()->getManager()->flush();
        return $this->reponse->success("Deleted");
    }
}
