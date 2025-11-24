<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationUser extends Model
{
    use HasFactory;

    protected $table    = 'organization_user';
    public $timestamps  = true;
    protected $fillable = [ 'id', 'user_id', 'organization_id', 'role', 'created_at', 'updated_at' ];
    protected $casts = [
    ];
}
