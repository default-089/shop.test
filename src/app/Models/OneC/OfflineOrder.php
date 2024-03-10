<?php

namespace App\Models\OneC;

use App\Admin\Models\AvailableSizesFull;
use App\Models\Product;
use App\Models\Size;
use App\Models\Stock;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Support\Carbon;

/**
 * @property string $ID
 * @property string $CODE
 * @property string $DESCR object description
 * @property string $ISMARK Flag Object is Marke
 * @property string $VERSTAMP Version stamp
 * @property string $SP6089 ДисконтнаяКарта
 * @property string $SP6090 КодДК
 * @property string $SP6091 Товар
 * @property string $SP6092 КодТовара
 * @property string $SP6093 Артикул
 * @property string $SP6094 НаименованиеТовар
 * @property string $SP6095 Магазин
 * @property string $SP6096 КодМагазина
 * @property string $SP6097 ДатаПродажи
 * @property string $SP6098 НомерЧека
 * @property string $SP6099 Количество
 * @property string $SP6100 Размер
 * @property string $SP6101 Сумма
 * @property string $SP6102 Телефон
 * @property string $SP6107 ВремяПродаж
 * @property string $SP6108 ДатаВозврата
 * @property string $SP6109 ВремяВозврата
 * @property string $SP6110 НомерВозврата
 *
 * @property-read Stock|null $stock
 * @property-read Product|null $product
 * @property-read Size|null $size
 */
class OfflineOrder extends AbstractOneCModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'SC6104';

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'CODE' => 'integer',
        'SP6092' => 'integer',
        'SP6096' => 'integer',
        'SP6100' => 'integer',
        'SP6101' => 'float',
    ];

    /**
     * Get the latest code by receipt number from the offline orders.
     */
    public static function getLatestCodeByReceipNumber(?string $receiptNumber): int
    {
        return (int)self::query()->where('SP6098', $receiptNumber)->value('CODE');
    }

    public function isReturn(): bool
    {
        return !empty($this->SP6109);
    }

    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class, 'SP6096', 'one_c_id');
    }

    public function availableSizes(): HasMany
    {
        return $this->hasMany(AvailableSizesFull::class, 'one_c_product_id', 'SP6092');
    }

    public function product(): HasOneThrough
    {
        return $this->hasOneThrough(
            Product::class,
            AvailableSizesFull::class,
            'one_c_product_id',
            'id',
            'SP6092',
            'product_id'
        )->withTrashed();
    }

    public function size(): BelongsTo
    {
        return $this->belongsTo(Size::class, 'SP6100', 'name');
    }

    public function getSoldAtDateTime(): Carbon
    {
        $date = Carbon::parse($this->SP6097);

        return $date->setTimeFromTimeString($this->SP6107);
    }
}
