<?php

namespace App\Controller\Api\Admin;

use App\Entity\Crud;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/admin')]
// pour l'admin on peut securiser les route comme ca,donc on doite mettre /api/admin/api avans la route specifier
class APiController extends AbstractController
{

    #[Route('/', name: 'home')]
    public function home(): Response
    {
        return $this->json([
            'status' => 1,
            'message' => 'success',
            'data' => 'Bienvenue dans mon site web'
        ], 200);
    }

    #[Route('/post_api', name: 'post_api', methods: 'POST')]
    public function post_api(Request $request,ValidatorInterface $validator): Response
    {
        $crud = new Crud();

        try{

            $title = $request->request->get('title');
            $content = $request->request->get('content');
            // $image=$request->files->get('image');
            $crud->setTitle($title);
            $crud->setContent($content);
            $em = $this->getDoctrine()->getManager();
            $errors= $validator->validate($crud);
            if(count($errors) > 0){
                return $this->json($errors,400);
            }
            $em->persist($crud);
            $em->flush();
            // $parametre = json_decode($request->getContent(), true);
            // $crud->setTitle($parametre['title']);
            // $crud->setContent($parametre['content']);
            // $em = $this->getDoctrine()->getManager();
            // $em->persist($crud);
            // $em->flush();
            // return $this->json([
            //     'status' => 1,
            //     'message' => 'success',
            //     'data' => 'Inserted avec success'
            // ], 200);
            return $this->json([
                'status' => 1,
                'message' => 'success',
                'data' => 'Inserted avec success'
            ], 200);
        }catch(NotEncodableValueException $e){
            return $this->json(
                [
                'status'=>400,
                'message'=>$e->getMessage(),
                ]
                );
        }


    }

    #[Route('/update_api/{id}', name: 'update_api', methods: 'PUT')]
    public function update_api(Request $request, $id): Response
    {
        $data = $this->getDoctrine()->getRepository(Crud::class)->find($id);
        $parametre = json_decode($request->getContent(), true);
        if(isset($parametre['title'])){
            $data->setTitle($parametre['title']);
        }
        if(isset($parametre['content'])){
            $data->setContent($parametre['content']);
        }
        $em = $this->getDoctrine()->getManager();
        $em->persist($data);
        $em->flush();
        return $this->json([
            'status' => 1,
            'message' => 'success',
            'data' => 'Updated avec success'
        ], 200);
    }

    #[Route('/delete_api/{id}', name: 'delete_api', methods: 'DELETE')]
    public function delete_api($id): Response
    {
        $data = $this->getDoctrine()->getRepository(Crud::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($data);
        $em->flush();
        return $this->json([
            'status' => 1,
            'message' => 'success',
            'data' => 'Deleted avec success'
        ], 200);
    }


    #[Route('/fetchall_api', name: 'fetchall_api', methods: 'GET')]
    public function fetchall_api(): Response
    {
        $data = $this->getDoctrine()->getRepository(Crud::class)->findAll();

        // foreach ($data as $d) {
        //     $res[] = [
        //         'id' => $d->getId(),
        //         'title' => $d->getTitle(),
        //         'content' => $d->getContent(),
        //     ];
        // }

        return $this->json([
            'status' => 1,
            'message' => 'success',
            'data' => $data
        ], 200);
    }



    #[Route('/fetch_api/{id}', name: 'fetch_api', methods: 'GET')]
    public function fetch_api($id): Response
    {
        $data = $this->getDoctrine()->getRepository(Crud::class)->find($id);
        return $this->json([
            'status' => 1,
            'message' => 'success',
            'data' => $data
        ], 200);
    }
}
