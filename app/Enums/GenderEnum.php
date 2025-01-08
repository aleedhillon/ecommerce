<?php
namespace App\Enums;

enum Gender : string
{
    case MALE = 'male';
    case FEMALE = 'female';
    case OTHER = 'other';
}

enum Status : int
{
    case DEACTIVE = 0;
    case ACTIVE = 1;
    case PENDING = 2;
    case REJECTED = 3;
}
