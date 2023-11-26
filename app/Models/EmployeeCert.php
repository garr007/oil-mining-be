<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Ramsey\Uuid\Uuid;

class EmployeeCert extends Model
{
    use HasFactory;

    protected $table = 'employee_certs';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    // do not have require validation
    public const RULES = [
        'employee_id' => 'numeric|exists:employees,user_id', //
        'code' => 'string|unique:employee_certs,code|min:2|max:20', //
        'date' => 'date', //
        'exp_date' => 'date',
        'type' => 'max:50', //
        'cert' => 'file|mimes:pdf|max:5120', //
    ];
    public const FILE_PATH = 'cert-employee';

    protected $guarded = ['id'];


    /**
     * Generate a new UUID for the model.
     */
    public static function newUuid(): string
    {
        return (string) Uuid::uuid4();
    }

    /**
     * get employee certificate file name
     * 
     * @return string
     */
    static public function getFileName($emp_id, $cert_uuid, $extension)
    {
        return $emp_id . '_' . "$cert_uuid." . $extension;
    }

    /**
     * get employee certificate download link
     * 
     * @return string
     */
    static public function getDownloadLink(string|null $fileName)
    {
        return $fileName ? route('employee.cert.download', ['fileName' => $fileName]) : null;
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'user_id');
    }


}
