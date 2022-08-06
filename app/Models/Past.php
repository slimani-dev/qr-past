<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Past
 *
 * @property string $id
 * @property string|null $content
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Past newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Past newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Past query()
 * @method static \Illuminate\Database\Eloquent\Builder|Past whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Past whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Past whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Past whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Past extends Model
{
    use Uuid, HasFactory;

    public $incrementing = false;
    protected $keyType = 'uuid';



    protected $fillable = ['uuid', 'content'];
}
