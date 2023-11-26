<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ResearchCategory extends Model
{
    use HasFactory;

    protected $table = 'research_categories';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $guarded = [
        'id'
    ];

    public const RULES = [
        'name' => 'required|string|min:2|max:25|unique:research_categories,name'
    ];

    public function research(): HasMany
    {
        return $this->hasMany(Research::class);
    }
}
