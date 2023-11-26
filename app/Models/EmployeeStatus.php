<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmployeeStatus extends Model
{
    use HasFactory;

    protected $table = 'employee_statuses';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $guarded = [];

    public const CONTRACT = 'CONTRACT';
    public const PERMANENT = 'PERMANENT';

    public const RULES = [
        'name' => 'required|string|min:1|max:15|uppercase|unique:employee_statuses,name',
    ];

    public function employee(): HasMany
    {
        return $this->hasMany(Employee::class);
    }
}
