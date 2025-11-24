<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    public function surveys()
    {
        return $this->hasMany(Survey::class);
    }
    public function Organizationusers()
    {
        return $this->hasMany(OrganizationUser::class);
    }
    use HasFactory;

    protected $table    = 'organizations';
    public $timestamps  = true;
    protected $fillable = [ 'id', 'name', 'user_id', 'created_at', 'updated_at' ];
    protected $casts = [
    ];
}
