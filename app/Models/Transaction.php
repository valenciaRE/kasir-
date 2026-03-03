namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'invoice',
        'subtotal',
        'discount_type',
        'discount_value',
        'discount_total',
        'tax_percent',
        'tax_total',
        'grand_total'
    ];

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }
}