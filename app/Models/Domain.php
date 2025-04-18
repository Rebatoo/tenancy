<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    protected $fillable = ['domain', 'tenant_id']; // Ensure these fields are fillable

    // Define the inverse relationship (a domain belongs to a tenant)
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
