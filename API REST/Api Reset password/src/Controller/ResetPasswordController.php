<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\ResetPassword\Controller\ResetPasswordControllerTrait;
use SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

class ResetPasswordController extends AbstractController
{
    use ResetPasswordControllerTrait;

    private $resetPasswordHelper;
    private $entityManager;

    public function __construct(ResetPasswordHelperInterface $resetPasswordHelper, EntityManagerInterface $entityManager)
    {
        $this->resetPasswordHelper = $resetPasswordHelper;
        $this->entityManager = $entityManager;
    }


    #[Route('/forgotPassword', name: 'forgotPassword', methods: 'POST')]
    public function request(MailerInterface $mailer): Response
    {
        $data = json_decode(file_get_contents('php://input'));
        if (!isset($data->email) || ($data->email === "")) {
            return $this->json(['error' => 'Form Invalid'], 400);
        }

        $user = $this->entityManager->getRepository(User::class)->findOneBy([
            'email' => $data->email,
        ]);

        if (!$user) {
            return $this->json([
                'Success' => "Error",
                "Message"=>"User n'existe pas !!!"
            ], 404);
        }

       
        $resetToken = $this->resetPasswordHelper->generateResetToken($user);
        $email = (new TemplatedEmail())
            ->from(new Address('Beckas@gmail.com', 'Beckas'))
            ->to($user->getEmail())
            ->subject('Your password reset request')
            ->htmlTemplate('reset_password/email.html.twig')
            ->context([
                'resetToken' => $resetToken,
            ]);

        $mailer->send($email);

        $this->setTokenObjectInSession($resetToken);

        return $this->json([
            'Success' => "Sucess",
            "Message"=>"Verifier votre email"
        ], 200);
    }



    #[Route('/reset/{token}', name: 'app_reset_password')]
    public function reset(UserPasswordHasherInterface $userPasswordHasher, string $token = null): Response
    {
        $data = json_decode(file_get_contents('php://input'));
        if (!isset($data->password) || ($data->password === "")) {
            return $this->json(['error' => 'Form Invalid'], 400);
        }
        if ($token) {
            $this->storeTokenInSession($token);
        }

        $token = $this->getTokenFromSession();
        if (null === $token) {
            return $this->json([
                'title' => "Error",
                "Message"=>"Cette Url ne contient pas de token !!!"
            ], 400);
        }
        try {
            $user = $this->resetPasswordHelper->validateTokenAndFetchUser($token);
        } catch (ResetPasswordExceptionInterface $e) {
            return $this->json([
                'title' => "Error",
                "Message"=>"Token Ivalide!!!"
            ], 400);
        }

            $this->resetPasswordHelper->removeResetRequest($token);
            $encodedPassword = $userPasswordHasher->hashPassword(
                $user,
                $data->password
            );
            $user->setPassword($encodedPassword);
            $this->entityManager->flush();

            $this->cleanSessionAfterReset();
            return $this->json([
                'title' => "Success",
                "Message"=>"Password Changed avec success!!!"
            ], 200);
    }

}
