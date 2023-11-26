<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Division extends Model
{
    use HasFactory;

    protected $table = 'divisions';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;


    public const MINING = 'Mining';
    public const FINANCE = 'Finance';
    public const RND = 'Research and Development';
    public const LEGAL = 'Legal';
    public const SALES = 'Sales and Marketing';
    public const HRD = 'Human resource development';
    public const IT = 'IT';

    public function positions(): HasMany
    {
        return $this->hasMany(Position::class);
    }

    protected $guarded = [];
}
