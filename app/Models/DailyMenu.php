<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyMenu extends Model
{
    use HasFactory;
    protected $table = 'daily_menus';
    protected $guarded = ['id', 'created_at', 'updated_at'];
    public static function dateExists(string $date): bool{
        return self::where('menu_date', $date)->exists();
    }
}
