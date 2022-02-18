<?php

namespace App\Shared;


class Messages
{
    public const SUCCESS = ['title'=>'Success','message'=>'Success','code'=>200];
    public const ERROR= ['title'=>'Error','message'=>'Error','code'=>400];
    public const FORM_INVALID= ['title'=>'Form invalid','message'=>'Some fields are empty','code'=>400];
    public const CATEGORY_NOT_FOUND= ['title'=>'Not found','message'=>'Category not found','code'=>404];
    public const ARTICLE_NOT_FOUND= ['title'=>'Not found','message'=>'Article not found','code'=>404];
    public const LOGIN_NOT_FOUND= ['title'=>'Not found','message'=>'Login not found','code'=>404];
}