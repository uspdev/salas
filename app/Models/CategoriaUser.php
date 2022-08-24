<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;
use OwenIt\Auditing\Contracts\Auditable;

class CategoriaUser extends Pivot  implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;

}
