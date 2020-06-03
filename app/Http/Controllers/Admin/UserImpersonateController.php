<?php

namespace App\Http\Controllers\Admin;

use App\Models\Logs\AdminActionOther;
use App\Models\MapEditorMap;
use App\Models\MapEditorObject;
use App\Models\SettingTest;
use App\Models\User;
use App\Services\MTAWorkerService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;
use Orchestra\Parser\Xml\Facade as XmlParser;

class UserImpersonateController extends Controller
{
    public function start(User $user)
    {
        abort_unless(auth()->user()->Id !== $user->Id && !auth()->user()->isImpersonated() && in_array(auth()->user()->Id, explode(',', env('IMPERSONATE_PERMISSION', ''))), 403);

        auth()->user()->impersonate($user);
        return redirect('/');
    }

    public function stop(User $user)
    {
        abort_unless(auth()->user()->isImpersonated() , 403);

        auth()->user()->leaveImpersonation();
        return redirect('/');
    }
}
