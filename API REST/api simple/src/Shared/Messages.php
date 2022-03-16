<?php

namespace App\Shared;


class Messages
{
    public const SUCCESS = ['title' => 'Success', 'message' => 'Success', 'code' => 200];
    public const ERROR = ['title' => 'Error', 'message' => 'Error', 'code' => 400];
    public const FORM_INVALID = ['title' => 'Form invalid', 'message' => 'Some fields are empty', 'code' => 400];
    public const CAR_NOT_FOUND = ['title' => 'Not found', 'message' => 'Car not found', 'code' => 404];
    public const COMMENT_NOT_FOUND = ['title' => 'Not found', 'message' => 'Comment not found', 'code' => 404];
    public const USER_NOT_FOUND = ['title' => 'Not found', 'message' => 'User not found', 'code' => 404];
    public const PASSWORD_WRONG = ['title' => 'Error', 'message' => 'Password wrong', 'code' => 500];
    public const EMAIL_USED = ['title' => 'Error', 'message' => 'Email used', 'code' => 500];
    public const PASSWORD_TOO_SHORT = ['title' => 'Error', 'message' => 'Password too short', 'code' => 500];
}
