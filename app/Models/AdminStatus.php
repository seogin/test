<?php

namespace App\Models;

enum AdminStatus: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
}
