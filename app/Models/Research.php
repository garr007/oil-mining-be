<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Ramsey\Uuid\Uuid;

class Research extends Model
{
    use HasFactory;

    protected $table = 'research';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;

    protected $guarded = [
        'id'
    ];

    /**
     * Generate a new UUID for the model.
     * 
     * *file name juga dari sini, tinggal tambah file extension
     * 
     * @return string
     */
    public static function newUuid(): string
    {
        return (string) Uuid::uuid4();
    }

    /**
     * get research doc file name
     * 
     * @return string
     */
    public static function getDocFileName($res_uuid, $extension): string
    {
        return "$res_uuid." . $extension;
    }

    /**
     * Generate download link for research doc
     * 
     * @return string
     */
    static public function getDownloadLink(string|null $fileName)
    {
        return $fileName ? route('research.doc.download', ['fileName' => $fileName]) : null;
    }

    public const DOC_FILE_PATH = 'research-doc';

    public const RULES = [
        'id' => 'numeric', //*

        'research_category_id' => 'numeric|exists:research_categories,id',
        'code' => 'size:6|unique:research,code,', //
        'name' => 'string|min:5|max:200', //
        'description' => 'string', //
        'start_date' => 'date',
        'due_date' => 'date|gte:start_date',
        'status' => 'in:ongoing,completed,delayed,at risk', //
        'doc' => 'file|mimes:pdf|max:10240',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ResearchCategory::class, 'research_category_id', 'id');
    }
}
