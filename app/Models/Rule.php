<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rule extends Model
{
    protected $fillable = [
        'experience_id',
        'name',
        'description',
        'category',
        'trigger_metric',
        'trigger_operator',
        'trigger_value',
        'action_type',
        'action_value',
        'action_unit',
        'active',
        'priority',
        'last_run_at',
    ];

    protected $casts = [
        'trigger_value' => 'decimal:2',
        'action_value'  => 'decimal:2',
        'active'        => 'boolean',
        'last_run_at'   => 'datetime',
    ];

    public function experience(): BelongsTo
    {
        return $this->belongsTo(Experience::class);
    }

    public function triggerLabel(): string
    {
        $metrics = [
            'confirmed_reservations' => 'reservas confirmadas',
            'pending_reservations'   => 'reservas pendentes',
            'total_reservations'     => 'total de reservas',
            'occupancy_pct'          => 'taxa de ocupação (%)',
        ];
        $operators = [
            'gte' => '≥', 'lte' => '≤', 'gt' => '>', 'lt' => '<', 'eq' => '=',
        ];

        $metric   = $metrics[$this->trigger_metric]   ?? $this->trigger_metric;
        $operator = $operators[$this->trigger_operator] ?? $this->trigger_operator;
        $value    = $this->trigger_metric === 'occupancy_pct'
            ? (int) $this->trigger_value . '%'
            : (int) $this->trigger_value;

        return "SE {$metric} {$operator} {$value}";
    }

    public function actionLabel(): string
    {
        return match ($this->action_type) {
            'block_date'     => '→ Bloquear data',
            'unblock_date'   => '→ Desbloquear data',
            'price_increase' => '→ Preço +' . ($this->action_unit === 'percent'
                ? (int) $this->action_value . '%'
                : number_format($this->action_value, 2, ',', '') . '€'),
            'price_decrease' => '→ Preço -' . ($this->action_unit === 'percent'
                ? (int) $this->action_value . '%'
                : number_format($this->action_value, 2, ',', '') . '€'),
            default          => '→ ' . $this->action_type,
        };
    }
}
