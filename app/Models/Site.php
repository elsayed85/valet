<?php

namespace App\Models;

use App\Valet\Valet;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public static function refreshAll()
    {
        try {
            $sites = (new Valet())->allSites();
            self::truncate();
            self::insert($sites->toArray());
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
}
