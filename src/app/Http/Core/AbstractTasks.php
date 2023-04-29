<?php
namespace App\Http\Core;
use App\Trait\GoogleAdTrait;
use App\Trait\ResponseTrait;

abstract class  AbstractTasks{
    use GoogleAdTrait, ResponseTrait;
}
