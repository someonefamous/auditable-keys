<?php

namespace SomeoneFamous\AuditableKeys\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use SomeoneFamous\FindBy\FindBy;

class AuditableKey extends Model
{
    use FindBy;
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function values()
    {
        return $this->hasMany(AuditableValue::class);
    }

    public function getCurrentValueAttribute()
    {
        return $this->getValueAt(Carbon::now()->timestamp);
    }

    public function getValueAt(int $timestamp)
    {
        $auditableValue =  $this->values()
            ->where('active_from', '<=', $timestamp)
            ->where(function($query) use ($timestamp)
            {
                $query->where('active_to', '>', $timestamp)
                    ->orWhereNull('active_to');
            })
            ->first();

        return $auditableValue ? $auditableValue->value : null;
    }

    public function updateValue($value)
    {
        $now = Carbon::now()->timestamp;

        $this->deleteValue($now);

        AuditableValue::create([
            'auditable_key_id' => $this->id,
            'active_from' => $now
        ]);
    }

    public function deleteValue($timestamp = null)
    {
        $timestamp = $timestamp ?: Carbon::now()->timestamp;

        $this->values()
            ->where('active_to', '>=', $timestamp)
            ->orWhereNull('active_to')
            ->update(['active_to' => $timestamp]);
    }
}
