<?php

namespace SomeoneFamous\AuditableKeys\Models;

use Illuminate\Database\Eloquent\Model;
use SomeoneFamous\FindBy\Traits\FindBy;
use SomeoneFamous\AuditableValues\Traits\AuditableValues;

class AuditableKey extends Model
{
    use AuditableValues;
    use FindBy;

    protected $fillable = [
        'name',
    ];
}
