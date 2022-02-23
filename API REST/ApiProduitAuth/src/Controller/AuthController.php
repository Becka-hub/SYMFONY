<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class AuthController extends AbstractController
{
    #[Route('/register', name: 'register', methods: 'POST')]
    public function register(UserRepository $userRepository, UserPasswordHasherInterface $hasher, ManagerRegistry $managerRegistry): Response
    {
        $data = json_decode(file_get_contents('php://input'));
        if (!isset($data->nom, $data->prenom, $data->adresse, $data->email, $data->password, $data->image,) || ($data->email === "" || $data->nom === "" || $data->password === "")) {
            return $this->json(['message' => "Form Invalid"], 400);
        }
        $users = $userRepository->findOneBy(["email" => $data->email]);
        if ($users) {
            return $this->json(['message' => 'Mail dejà utiliser'], 400);
        }
        if (strlen($data->password) < 6) {
            return $this->json(['message' => 'Mot de passe trop court '], 400);
        }

        $image_name = time() . '.png';
        $fileName = $this->getParameter('brochures_directory_user') . $image_name;
        $file = fopen($fileName, 'wb');
        $datas = explode(',', $data->image);
        fwrite($file, base64_decode(count($datas) === 2 ? $datas[1] : $datas[0]));
        fclose($file);

        $user = new User();
        $user->setNom($data->nom);
        $user->setPrenom($data->prenom);
        $user->setAdresse($data->adresse);
        $user->setEmail($data->email);
        $user->setRoles("ROLE_USER");
        $user->setPassword($hasher->hashPassword(
            $user,
            $data->password
        ));
        $user->setImage($image_name);
        $managerRegistry->getManager()->persist($user);
        $managerRegistry->getManager()->flush();
        return $this->json(['title'=>'success','message' => 'Inscription avec success', 'donner' => $user], 200);
    }

    #[Route('/auth', name: 'auth', methods: 'POST')]
    public function auth(UserRepository $userRepository, JWTTokenManagerInterface $tokenManager,UserPasswordHasherInterface $hasher)
    {
        $data = json_decode(file_get_contents('php://input'));
        if (!isset($data->email, $data->password) || ($data->email === "" || $data->password === "")) {
            return $this->json(['message' => 'Form Invalid'], 400);
        }
        $user = $userRepository->findOneBy(["email" => $data->email]);
        if (!$user) {
            return $this->json(['message'=>'user n\'existe pas'],400);
        }
        if (!$hasher->isPasswordValid($user, $data->password)) {
            return $this->json(['message'=>'mot de passe incorrect'],400);
        }
        return $this->json(['title'=>'success','donner'=>$user,'token' => $tokenManager->create($user)],200);
    }
}
