<?php

namespace SomeoneFamous\AuditableKeys\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use SomeoneFamous\FindBy\FindBy;

use Illuminate\Database\Eloquent\Model;

class AuditableValue extends Model
{
    use FindBy;
    use HasFactory;

    const TYPE_STRING = 0;

    protected $fillable = [
        'auditable_key_id',
        'type',
        'value',
        'active_from',
        'active_to'
    ];

    protected $dates = [
        'active_from',
        'active_to'
    ];

    public function auditable_key()
    {
        return $this->belongsTo(AuditableKey::class);
    }
}
