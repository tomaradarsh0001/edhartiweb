<?php

namespace App\Helpers;

use App\Models\UserActionLog;
use Illuminate\Support\Facades\Auth;
use App\Models\Module;
use Carbon\Carbon;

class UserActionLogHelper
{
    public static function UserActionLog($action, $url, $moduleName, $description = null)
    {
        $module = Module::where('name', $moduleName)->first();
        if (empty($module)) {
            $newModule =  Module::create(['name' => $moduleName, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]);
            $moduleId = $newModule->id;
        } else {
            $moduleId = $module->id;
        }
        // Check if a similar log entry already exists for the same user, module, and action
        // $existingLog = UserActionLog::where('user_id', Auth::id())
        // ->where('module_id', $moduleId)
        // ->where('action', $action)
        // ->first();

        // if (!$existingLog) {
            // Create a new log entry if no similar entry exists
            $log = new UserActionLog();
            $log->user_id = Auth::id();
            $log->module_id = $moduleId;
            $log->action = $action;
            $log->url = $url;
            $log->description = $description;
            $log->save();
        // }
    }
}
