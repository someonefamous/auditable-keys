<?php

namespace SomeoneFamous\AuditableKeys\Models;

use Illuminate\Database\Eloquent\Model;
use SomeoneFamous\FindBy\Traits\FindBy;

class AuditableValue extends Model
{
    use FindBy;

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
