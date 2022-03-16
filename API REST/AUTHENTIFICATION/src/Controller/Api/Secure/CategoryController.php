<?php

namespace App\Controller\Api\Secure;

use App\Entity\Category;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api', name: 'category_ctrl')]
#[Security("is_granted('ROLE_USER')")]
class CategoryController extends AbstractController
{

    private ManagerRegistry $managerRegistry;
    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }
    #[Route('/category', name: 'category', methods: 'POST')]
    public function category(): Response
    {
        $data = json_decode(file_get_contents('php://input'));
        if (!isset($data->nameCategory) || ($data->nameCategory === "")) {
            return $this->json(['Warning' => 'Form Invalid'], 500);
        }
        $category = new Category();
        $category->setNameCategory($data->nameCategory);

        $this->managerRegistry->getManager()->persist($category);
        $this->managerRegistry->getManager()->flush();
        return $this->json(['status' => true, 'title' => "success", "message" => "Insertion avec success", "donner" => $category], 200);
    }
}
