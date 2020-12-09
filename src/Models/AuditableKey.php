<?php

namespace SomeoneFamous\AuditableKeys\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use SomeoneFamous\FindBy\Traits\FindBy;

class AuditableKey extends Model
{
    use FindBy;

    protected $fillable = [
        'name',
    ];

    public function values()
    {
        return $this->hasMany(AuditableValue::class);
    }

    public function getCurrentValueAttribute()
    {
        return $this->getValueAt(Carbon::now());
    }

    public function getValueAt(Carbon $time)
    {
        $auditableValue =  $this->values()
            ->where('active_from', '<=', $time)
            ->where(function($query) use ($time) {
                $query->where('active_to', '>', $time)->orWhereNull('active_to');
            })
            ->first();

        return $auditableValue ? $auditableValue->cast_value : null;
    }

    public function updateValue($value = null)
    {
        $now = Carbon::now();

        $this->deleteValue($now);

        $this->values()->create([
            'active_from' => $now,
            'value' => $value
        ]);
    }

    public function deleteValue(?Carbon $time)
    {
        $time = $time ?: Carbon::now();

        $this->values()
            ->where('active_to', '>=', $time)
            ->orWhereNull('active_to')
            ->update(['active_to' => $time]);
    }
}
