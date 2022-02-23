<?php

namespace App\Shared;


class Messages
{
    public const SUCCESS = ['title' => 'Success', 'message' => 'Success', 'code' => 200];
    public const ERROR = ['title' => 'Error', 'message' => 'Error', 'code' => 400];
    public const FORM_INVALID = ['title' => 'Form invalid', 'message' => 'Champ de text vide !!!', 'code' => 400];
    public const VAGUE_NOT_FOUND = ['title' => 'Not found', 'message' => 'Vague non trouver !!!', 'code' => 404];
    public const USER_NOT_FOUND = ['title' => 'Not found', 'message' => 'Etudiant non trouver !!!', 'code' => 404];
    public const PASSWORD_WRONG = ['title' => 'Error', 'message' => 'Mot de passe incorrect !!!', 'code' => 500];
    public const EMAIL_USED = ['title' => 'Error', 'message' => 'Mail déjas utiliser !!!', 'code' => 500];
    public const PASSWORD_TOO_SHORT = ['title' => 'Error', 'message' => 'Mot de passe trôp court !!!', 'code' => 500];
}
