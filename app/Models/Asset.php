<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    protected $fillable=['sub_category_id','location_id','branch_id','user_id','code','descriptions','acquired_date','warranty_date','purchase_price','manufacturer','model','status','status_description'];

    public function subcategory(){
        return $this->belongsTo(AssetSubCategory::class,'sub_category_id','id');
    }

    public function branch(){
        return $this->belongsTo(Branch::class,'branch_id','id');
    }

    public function location(){
        return $this->belongsTo(AssetLocation::class,'location_id','id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function repairLogs()
    {
        return $this->hasMany(AssetRepairLog::class);
    }

    public function useLogs()
    {
        return $this->hasMany(AssetUseLog::class);
    }

}
