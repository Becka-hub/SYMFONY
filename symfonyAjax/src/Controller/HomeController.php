<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'Home',
        ]);
    }

    #[Route('/addCategory', name: 'addCategory', methods: 'POST')]
    public function addCategory(Request $request): Response
    {
        $title = $request->request->get('categorieName');
        $image = $request->files->get('categorieImage');
        $extension = $image->getClientOriginalExtension();
        $image_name = time() . '.' . $extension;
        $image->move($this->getParameter('brochures_directory'), $image_name);
        $category = new Category();
        $category->setName($title);
        $category->setImage($image_name);
        $em = $this->getDoctrine()->getManager();
        $em->persist($category);
        $em->flush();
        return $this->json(['success' => 'Insertion category avec succcess !'], 200);
    }


    #[Route('/afficheCategory', name: 'afficheCategory', methods: 'GET')]
    public function afficheCategory(CategoryRepository $cat): Response
    {
        $category = $cat->findAll();
        return $this->json($category, 200);
    }


    #[Route('/deletaCategory/{id}', name: 'deleteCategory', methods: 'DELETE')]
    public function deleteCategory($id, CategoryRepository $cat): Response
    {
        $category = $cat->find($id);
        $image = $category->getImage();
        $nomImage = $this->getParameter('brochures_directory') . '/' . $image;
        if (file_exists($nomImage)) {
            unlink($nomImage);
        }
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($category);
        $entityManager->flush();
        return $this->json(['success' => 'Suppression avec success !'], 200);
    }

    #[Route('/onCategory/{id}', name: 'onCategory', methods: 'GET')]
    public function onCategory($id, CategoryRepository $cat): Response
    {
        $category = $cat->find($id);
        return $this->json($category, 200);
    }


    #[Route('/modifierCategory', name: 'modifierCategory', methods: 'POST')]
    public function modifierCategory(Request $request,CategoryRepository $cat): Response
    {
        $title = $request->request->get('ModifierCategorieName');
        $image = $request->files->get('ModifierCategorieImage');
        $nomImage = $request->request->get('ModifierCategorieNomImage');
        $id=$request->request->get('ModifierCategorieId');

        if($image !=""){
            $extension = $image->getClientOriginalExtension();
            $image_name = time() . '.' . $extension;
            $image->move($this->getParameter('brochures_directory'), $image_name);
            $category=$cat->find($id);
            $encienImage=$this->getParameter('brochures_directory') . '/' .$category->getImage();
            if (file_exists($encienImage)) {
                unlink($encienImage);
            }
            $category->setName($title);
            $category->setImage($image_name);
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            
        }else{
            $category=$cat->find($id);
            $category->setName($title);
            $category->setImage($nomImage);
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
        }
        $em->flush();
        return $this->json(['success' => 'Modification category avec succcess !'], 200);
    }
}
