<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * App\Models\Past
 *
 * @property int $id
 * @property string $code
 * @property string|null $content
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection|Media[] $media
 * @property-read int|null $media_count
 * @method static \Illuminate\Database\Eloquent\Builder|Past newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Past newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Past query()
 * @method static \Illuminate\Database\Eloquent\Builder|Past whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Past whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Past whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Past whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Past whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Past extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = ['content', 'code'];

    protected $appends = ['files'];

    public function files(): Attribute
    {
        return Attribute::get(function () {
            $files = [];
            $mediaFiles = $this->getMedia('files');

            foreach ($mediaFiles as /* @var $mediaFile Media */ $mediaFile) {
                $files[] = [
                    'id' => $mediaFile->id,
                    'url' => $mediaFile->getTemporaryUrl($this->created_at->addDay()),
                    'type' => $mediaFile->type,
                    'name' => $mediaFile->name,
                    'size' => $mediaFile->size,
                ];
            }

            return $files;
        });
    }

    /**
     * @param int $num
     * @param int $b
     * @return string
     */
    static function toBase(int $num, int $b = 62): string
    {
        $base = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $r = $num % $b;
        $res = $base[$r];
        $q = floor($num / $b);
        while ($q) {
            $r = $q % $b;
            $q = floor($q / $b);
            $res = $base[$r] . $res;
        }
        return $res;
    }

    /**
     * @param string $num
     * @param int $b
     * @return float|bool|int
     */
    static function to10(string $num, int $b = 62): float|bool|int
    {
        $base = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $limit = strlen($num);
        $res = strpos($base, $num[0]);
        for ($i = 1; $i < $limit; $i++) {
            $res = $b * $res + strpos($base, $num[$i]);
        }
        return $res;
    }
}
