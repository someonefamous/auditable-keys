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

    public function getValueAt(Carbon $timestamp)
    {
        $auditableValue =  $this->values()
            ->where('active_from', '<=', $timestamp)
            ->where(function($query) use ($timestamp) {
                $query->where('active_to', '>', $timestamp)->orWhereNull('active_to');
            })
            ->first();

        return $auditableValue ? $auditableValue->cast_value : null;
    }

    public function updateValue($value)
    {
        $now = Carbon::now();

        $this->deleteValue($now);

        AuditableValue::create([
            'auditable_key_id' => $this->id,
            'active_from' => $now,
            'value' => $value
        ]);
    }

    public function deleteValue(?Carbon $timestamp = null)
    {
        $timestamp = $timestamp ?: Carbon::now();

        $this->values()
            ->where('active_to', '>=', $timestamp)
            ->orWhereNull('active_to')
            ->update(['active_to' => $timestamp]);
    }
}
