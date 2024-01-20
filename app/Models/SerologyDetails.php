<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SerologyDetails extends Model
{
    use HasFactory;
    protected $table = 'ho_serology_details';
    protected $fillable = ['sm_id'];
    public function service() 
    { 
        return $this->hasOne(HealthSafetySetupDataService::class, 'id', 'ho_service_id'); 
    }
    public function method() 
    { 
        return $this->hasOne(SerologyMethod::class, 'id', 'sm_id'); 
    }
}
