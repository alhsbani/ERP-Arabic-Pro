<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentPermission extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'document_id',
        'user_id',
        'role_id',
        'permission_type',
    ];

    /**
     * Get the document that owns the permission.
     */
    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    /**
     * Get the user that owns the permission.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the role that owns the permission.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
