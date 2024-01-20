<?php
namespace App\Traits;

use Carbon\Carbon;
use App\Models\CommonModelmaster;
use Schema;
/**
 * trait ModelUpdateCreate
 */

trait ModelUpdateCreate
{
    public static function bootModelUpdateCreate()
    {
        // updating created_by and updated_by when model is created
        static::creating(function ($model) {
            $tableName = $model->getTable();
            $user_trigger_id = 0;
            $user_trigger_name = 'System';
            if (auth()->user()) {
                $user_trigger_id = auth()->user()->id;
                $user_trigger_name = auth()->user()->name;
            }
            if (Schema::hasColumn($tableName,'created_by')) {
                // dd(auth()->user());
                if (auth()->user()) {
                    $model->created_by = $user_trigger_id;
                }
            }
            if (Schema::hasColumn($tableName,'created_at')) {
                $model->created_at = Carbon::now();
            }
            if (Schema::hasColumn($tableName,'created_date')) {
                $model->created_date = Carbon::now();
            }

            if (Schema::hasColumn($tableName,'modified_by')) {
                if (auth()->user()) {
                    $model->modified_by = $user_trigger_id;
                }
            }
            if (Schema::hasColumn($tableName,'modified_date')) {
                $model->modified_date = Carbon::now();
            }
            if (Schema::hasColumn($tableName,'updated_by')) {
                if ($model->isClean('updated_by')) {
                    if (auth()->user()) {
                        $model->updated_by = $user_trigger_id;
                    }
                }
            }
            if (Schema::hasColumn($tableName,'updated_at')) {
                $model->updated_at = Carbon::now();
            }
            
            if (auth()->user()) {
                $logDetails['log_content'] = "User '".$user_trigger_name."' Added ".$model->id." In Table ".str_replace('_', ' ',$model->getTable()); 
                $logDetails['module_id'] = $model->id;
                $common = new CommonModelmaster();
                $common->updateLog($logDetails);
            }
        });

        // updating updated_by when model is updated
        static::updating(function ($model) {
            $tableName = $model->getTable();
            $user_trigger_id = 0;
            $user_trigger_name = 'System';

            if (auth()->user()) {
                $user_trigger_id = auth()->user()->id;
                $user_trigger_name = auth()->user()->name;
            }
            // dd(auth());
            if ($model->isDirty('is_active')) {
                // dd(Schema::getColumnListing($tableName));
                $action = $is_activeinactive==1?'Restored':'Soft Deleted';
                $logDetails['module_id'] =$id;
                $logDetails['log_content'] = "User '".\Auth::user()->name."' Assistance ".$action; 
            } else {
                if (Schema::hasColumn($tableName,'modified_by')) {
                    $model->modified_by = $user_trigger_id;
                }
                if (Schema::hasColumn($tableName,'modified_date')) {
                    $model->modified_date = Carbon::now();
                }
                if (Schema::hasColumn($tableName,'updated_by')) {
                    if (!$model->isDirty('is_active')) {
                        $model->updated_by = $user_trigger_id;
                    }
                }
                if (Schema::hasColumn($tableName,'updated_at')) {
                    $model->updated_at = Carbon::now();
                }
                $logDetails['log_content'] = "User '".$user_trigger_name."' Updated ".$model->id." In Table ".str_replace('_', ' ',$tableName); 
                $logDetails['module_id'] = $model->id;
            }
            $common = new CommonModelmaster();
            $common->updateLog($logDetails);
        });
    }
}