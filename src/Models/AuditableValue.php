<?php

namespace SomeoneFamous\AuditableKeys\Models;

use Illuminate\Database\Eloquent\Model;
use SomeoneFamous\FindBy\Traits\FindBy;

class AuditableValue extends Model
{
    use FindBy;

    const TYPE_STRING = 0;
    const TYPE_INT = 1;
    const TYPE_FLOAT = 2;

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

    public function getCastValueAttribute()
    {
        switch ($this->type) {

            case static::TYPE_STRING:

                $value = (string) $this->value;
                break;

            case static::TYPE_INT:

                $value = (int) $this->value;
                break;

            case static::TYPE_FLOAT:

                $value = (float) $this->value;
                break;

            default: $value = $this->value;
        }

        return $value;
    }
}
