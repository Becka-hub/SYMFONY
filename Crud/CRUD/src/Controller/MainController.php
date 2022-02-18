<?php

namespace App\Controller;

use App\Entity\Crud;
use App\Form\CrudType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    #[Route('/', name: 'main')]
    public function index(): Response
    {
        $data=$this->getDoctrine()->getRepository(Crud::class)->findAll();
        return $this->render('main/index.html.twig', [
            'controller_name' => 'Main',
            'data'=>$data
        ]);
    }

    #[Route('/create', name: 'create')]
    public function create(Request $request)
    {
        $crud = new Crud();
        $form = $this->createForm(CrudType::class, $crud);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->persist($crud);
            $em->flush();

            $this->addFlash('notice','Submitted Successfully');

            return $this->redirectToRoute('main');
        }
        return $this->render('main/create.html.twig', ['form' => $form->createView(),'controller_name'=>'Create']);
    }

    #[Route('/update/{id}', name: 'update')]
    public function update(Request $request,$id)
    {
        $crud = $this->getDoctrine()->getRepository(Crud::class)->find($id);
        $form = $this->createForm(CrudType::class, $crud);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->persist($crud);
            $em->flush();

            $this->addFlash('notice','Updated Successfully');

            return $this->redirectToRoute('main');
        }
        return $this->render('main/update.html.twig', ['form' => $form->createView(),'controller_name'=>'Create']);
    }

    
    #[Route('/delete/{id}', name: 'detele')]
    public function detele($id)
    {
        $data=$this->getDoctrine()->getRepository(Crud::class)->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($data);
        $em->flush();
        $this->addFlash('notice','delete Successfully');
        return $this->redirectToRoute('main');
    }
}
