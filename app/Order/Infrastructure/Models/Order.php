<?php

namespace App\Order\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Order
 *
 * @property int $id
 * @property string $sum
 * @property string $contractorType
 * @property string $createdAt
 *
 */
class Order extends Model
{
    protected $fillable = ['sum', 'contractorType'];
}
