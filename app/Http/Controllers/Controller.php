<?php

namespace App\Http\Controllers;

// Make sure these 'use' statements are present
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

// Make sure it 'extends BaseController'
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
