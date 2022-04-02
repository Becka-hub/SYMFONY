<?php

namespace App\Controller;

use App\Entity\Employe;
use App\Service\Service;
use App\Repository\EmployeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EmployeController extends AbstractController
{
    private Service $service;

    public function __construct(Service $service) 
    {
        $this->service = $service;
    }


    #[Route('/employe', name: 'employe')]
    public function index(): Response
    {
        return $this->render('employe/index.html.twig', [
            'controller_name' => 'Employees',
        ]);
    }
    #[Route('/addEmploye', name: 'addEmploye', methods: 'POST')]
    public function addEmploye(Request $request): Response
    {
        $name = $request->request->get('name');
        $lastName=$request->request->get('lastName');
        $email=$request->request->get('email');
        $telephone=$request->request->get('telephone');
        $profession=$request->request->get('profession');
        $pays=$request->request->get('pays');
        $ville=$request->request->get('ville');
        $photo = $request->files->get('photo');
        $image_name=$this->service->uploadFile($photo,$this->getParameter('brochures_directory'));
        $employe= new Employe();
        $employe->setName($name);
        $employe->setLastName($lastName);
        $employe->setEmail($email);
        $employe->setPhone($telephone);
        $employe->setJob($profession);
        $employe->setCountry($pays);
        $employe->setCity($ville);
        $employe->setPhoto($image_name);

        $em = $this->getDoctrine()->getManager();
        $em->persist($employe);
        $em->flush();
        return $this->json(['success' => 'Add employee success !'], 200);
}

#[Route('/afficheEmploye', name: 'afficheEmploye', methods: 'GET')]
public function afficheEmploye(EmployeRepository $employe): Response
{
    $employess = $employe->findAll();
    return $this->json(array_map(function (Employe $emp) {
        return $emp->tojson();
    }, $employess), 200);
}


#[Route('/deletaEmploye/{id}', name: 'deletaEmploye', methods: 'DELETE')]
public function deletaEmploye($id, EmployeRepository $employe): Response
{
    $employess = $employe->find($id);
    $image = $employess->getPhoto();
    $nomImage = $this->getParameter('brochures_directory') . '/' . $image;
    if (file_exists($nomImage)) {
        unlink($nomImage);
    }
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->remove($employess);
    $entityManager->flush();
    return $this->json(['success' => 'Delete employee success !'], 200);
}


#[Route('/onEmploye/{id}', name: 'onEmploye', methods: 'GET')]
public function onEmploye($id, EmployeRepository $employe): Response
{
    $employess = $employe->find($id);
    return $this->json($employess->tojson()
    , 200);
}


#[Route('/modifierEmploye', name: 'modifierEmploye', methods: 'POST')]
public function modifierCategory(Request $request,EmployeRepository $employe): Response
{
    $name = $request->request->get('ModifierEmployeName');
    $lastName = $request->request->get('ModifierEmployeLastName');
    $email = $request->request->get('ModifierEmployeEmail');
    $telephone = $request->request->get('ModifierEmployeTelephone');
    $profession = $request->request->get('ModifierEmployeProfession');
    $pays = $request->request->get('ModifierEmployePays');
    $ville = $request->request->get('ModifierEmployeVille');;

    $image = $request->files->get('ModifierEmployeImage');
    $nomImage = $request->request->get('ModifierEmployeNomImage');
    $id=$request->request->get('ModifierEmployeId');

    if($image !=""){
        $extension = $image->getClientOriginalExtension();
        $image_name = time() . '.' . $extension;
        $image->move($this->getParameter('brochures_directory'), $image_name);
        $employess=$employe->find($id);
        $encienImage=$this->getParameter('brochures_directory') . '/' .$employess->getPhoto();
        if (file_exists($encienImage)) {
            unlink($encienImage);
        }
        $employess->setName($name);
        $employess->setLastName($lastName);
        $employess->setEmail($email);
        $employess->setPhone($telephone);
        $employess->setCountry($pays);
        $employess->setCity($ville);
        $employess->setPhoto($image_name);
        $em = $this->getDoctrine()->getManager();
        $em->persist($employess);
        
    }else{
        $employess=$employe->find($id);
        $employess->setName($name);
        $employess->setLastName($lastName);
        $employess->setEmail($email);
        $employess->setPhone($telephone);
        $employess->setCountry($pays);
        $employess->setCity($ville);
        $employess->setPhoto($nomImage);
        $em = $this->getDoctrine()->getManager();
        $em->persist($employess);
    }
    $em->flush();
    return $this->json(['success' => 'Update employee success !'], 200);
}


}