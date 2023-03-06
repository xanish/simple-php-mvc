<?php

namespace App\Enums;

enum RequestMethod: string
{
    case HEAD = 'head';
    case GET = 'get';
    case POST = 'post';
    case PUT = 'put';
    case PATCH = 'patch';
    case DELETE = 'delete';
}
