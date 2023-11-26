<?php

namespace App\Models;

use App\Models\Position;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use HasFactory;

    protected $table = 'employees';
    protected $primaryKey = 'user_id';
    public $incrementing = false;
    protected $keyType = 'int';
    public $timestamps = false;

    public const RULES = [
        'religion' => 'string|max:20',
        'phone' => 'required|string|regex:/^[0-9]+$/|max:20',
        'address' => 'required|string|max:100',
        'birth_date' => 'required|date',
        'social_number' => 'required|string|min:15|max:16',
        'img' => 'mimes:jpeg,png,jpg|max:5120',
        'employee_status_id' => 'required|numeric|exists:employee_statuses,id',
        'entry_date' => 'required|date',
        'division_id' => 'required|numeric|exists:divisions,id',
        'position_id' => 'required|numeric|exists:positions,id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(EmployeeStatus::class, 'employee_status_id');
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(EmployeeCert::class, 'employee_id', 'user_id');
    }

    protected $guarded = [];
}
