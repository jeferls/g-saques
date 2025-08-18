<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string|null $transfer_id
 * @property string $gateway
 * @property string|null $status
 * @property int $amount
 * @property int|null $fee
 * @property string|int|null $source_type
 * @property string|null $source_id
 * @property string|null $type
 * @property string|null $target_type
 * @property string|null $target_id
 * @property string|null $idempotency_key
 * @property string|null $reason
 * @property object|null $created_at
 */
class Transfer extends Model
{
    protected $table = 'transfers';

    protected $fillable = [
        'transfer_id',
        'gateway',
        'status',
        'amount',
        'fee',
        'source_type',
        'source_id',
        'type',
        'target_type',
        'target_id',
        'idempotency_key',
        'reason'
    ];

    protected $casts = [
        'amount' => 'integer',
        'fee' => 'integer',
        'idempotency_key' => 'string'
    ];
}
