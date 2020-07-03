<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('screens', function() {
    if(auth() && auth()->user() && auth()->user()->Rank >= 7) {
        return view('screen');
    }
    return redirect('/');
});

Route::namespace('Auth')->prefix('auth')->group(function () {
    Route::get('login', 'LoginController@showLoginForm')->name('login');
    Route::post('login', 'LoginController@login');
    Route::post('logout', 'LoginController@logout')->name('logout');
});

Route::get('who-is-online', 'WhoIsOnlineController@index')->name('who.is.online');
Route::get('statistics', 'StatisticsController@index')->name('statistics');
Route::get('commits', 'CommitController@index')->name('commits');
Route::get('achievements', 'AchievementsController@index')->name('achievements');

Route::resource('groups', 'GroupController')->only('index', 'show');
Route::get('groups/{group}/logs/{log?}', 'GroupLogController@show')->name('groups.show.logs');
Route::get('groups/{group}/statistics/{statistic?}', 'GroupStatisticController@show')->name('groups.show.statistics');
Route::get('groups/{group}/{page}', 'GroupController@show')->name('groups.show.page');

Route::resource('factions', 'FactionController')->only('index', 'show');
Route::get('factions/{faction}/logs/{log?}', 'FactionLogController@show')->name('factions.show.logs');
Route::get('factions/{faction}/statistics/{statistic?}', 'FactionStatisticController@show')->name('factions.show.statistics');
Route::get('factions/{faction}/{page}', 'FactionController@show')->name('factions.show.page');

Route::resource('companies', 'CompanyController')->only('index', 'show');
Route::get('companies/{company}/logs/{log?}', 'CompanyLogController@show')->name('companies.show.logs');
Route::get('companies/{company}/statistics/{statistic?}', 'CompanyStatisticController@show')->name('companies.show.statistics');
Route::get('companies/{company}/{page}', 'CompanyController@show')->name('companies.show.page');

Route::middleware('auth')->group(function () {
    Route::get('users/search', 'UserSearchController@index')->name('users.search');
    Route::resource('users', 'UserController')->only('index', 'show');
    Route::get('users/{user}/logs/{log?}', 'UserLogController@show')->name('users.show.logs');
    Route::get('users/{user}/{page}', 'UserController@show')->name('users.show.page');

    Route::get('companies/{company}/{page}', 'CompanyController@show')->name('companies.show.page');
    Route::resource('textures', 'TextureController')->only(['index', 'create', 'store', 'destroy']);
    Route::resource('teamspeak', 'TeamspeakController');

    Route::resource('trainings/templates', 'Training\\TrainingTemplateController', [
        'as' => 'trainings'
    ]);
    Route::get('trainings/templates/{template}/delete', 'Training\\TrainingTemplateController@delete')->name('trainings.templates.delete');

    Route::resource('trainings/templates.contents', 'Training\\TrainingTemplateContentController', [
        'as' => 'trainings'
    ])->only(['create', 'store']);
    Route::resource('trainings/templates/contents', 'Training\\TrainingTemplateContentController', [
        'as' => 'trainings.templates'
    ])->except(['create', 'store', 'index', 'show']);;
    Route::get('trainings/templates/contents/{content}/delete', 'Training\\TrainingTemplateContentController@delete')->name('trainings.templates.contents.delete');

    Route::resource('trainings/templates.trainings', 'Training\\TrainingTemplateTrainingController', [
        'as' => 'trainings'
    ])->only(['create', 'store']);

    Route::resource('trainings/permissions', 'Training\\PermissionController', [
        'as' => 'trainings'
    ])->only(['index', 'edit', 'update']);

    Route::resource('trainings/overview', 'Training\\OverviewController', [
        'as' => 'trainings'
    ])->only(['index']);

    Route::get('/trainings/{training}', [
        'uses' => 'Training\\TrainingController@index',
        'as' => 'trainings.show',
    ]);
    Route::get('/trainings/{path?}', [
        'uses' => 'Training\\TrainingController@index',
        'as' => 'trainings.index',
        'where' => ['path' => '.*']
    ]);

    Route::get('/tickets/{path?}', [
        'uses' => 'TicketController@index',
        'as' => 'tickets.index',
        'where' => ['path' => '.*']
    ]);

    Route::namespace('Shop')->middleware('admin')->prefix('shop')->group(function () { //Temporary cause shop is not finished
    //Route::namespace('Shop')->prefix('shop')->group(function () {
        Route::resource('/', 'DashboardController')->only('index');
        Route::resource('dashboard', 'DashboardController')->only('index');
        Route::prefix('charge')->group(function() {
            Route::get('/', 'ChargeController@index')->name('charge.index');
            Route::get('/{type}', 'ChargeController@create')->name('charge.create');
            Route::post('/', 'ChargeController@store')->name('charge.store');
            Route::get('/status/{status}', 'ChargeController@status')->name('charge.status');
        });
    });

    Route::namespace('Event')->prefix('events')->name('events.')->group(function () {
        Route::resource('santa', 'SantaController')->only(['index', 'store', 'create']);
    });


    Route::namespace('Admin')->prefix('admin')->group(function () {
        Route::post('users/{user}/impersonate', 'UserImpersonateController@start')->name('admin.users.impersonate.start');
        Route::post('users/{user}/impersonate/stop', 'UserImpersonateController@stop')->name('admin.users.impersonate.stop');
    });

    Route::namespace('Admin')->middleware('admin')->prefix('admin')->group(function () {
        Route::resource('dashboard', 'DashboardController', ['as' => 'admin'])->only('index');
        Route::resource('vehicles', 'VehicleController', ['as' => 'admin'])->only('index');
        Route::resource('bans', 'BanController', ['as' => 'admin'])->only('index');
        Route::resource('maps', 'MapController', ['as' => 'admin'])->only('index', 'create', 'store');
        Route::resource('users', 'UserController', ['as' => 'admin'])->only('update');
        Route::resource('users.screenshots', 'ScreenshotUserController', ['as' => 'admin'])->only('index', 'store');
        Route::resource('users.teamspeak', 'UserTeamspeakController', ['as' => 'admin'])->only('create', 'store');
        Route::get('teamspeak/{teamspeak}/delete', 'TeamspeakController@delete')->name('admin.teamspeak.delete');
        Route::resource('teamspeak', 'TeamspeakController', ['as' => 'admin'])->only('index', 'destroy', 'show');
        Route::get('logs/{log?}', 'LogController@show')->name('admin.logs.show');
        Route::get('users/multiaccounts', 'MultiaccountController@index')->name('admin.user.multiaccounts');
        Route::get('users/forum/{forumId}', 'ForumUserController@show')->name('admin.user.forum');
        Route::get('textures', 'TextureController@index')->name('admin.texture');
        Route::get('server', 'ServerController@show')->name('admin.server.show');
        Route::post('server', 'ServerController@action')->name('admin.server.action');
        Route::post('users/{user}/impersonate', 'UserImpersonateController@start')->name('admin.users.impersonate.start');
        Route::get('server/edit/password', 'ServerController@editPassword')->name('admin.server.editPassword');
        Route::patch('server/edit/password', 'ServerController@updatePassword')->name('admin.server.updatePassword');
    });

    if(env('TEAMSPEAK_TROLL_ENABLED') === true) {
        Route::get('/' . env('TEAMSPEAK_TROLL_URI'), function () {

            $button = new \App\Models\Logs\MagicButton();
            $button->UserId = auth()->user()->Id;
            $button->save();

            $target = env('TEAMSPEAK_TROLL_TARGET');

            $client = new \GuzzleHttp\Client();

            $result = $client->get(env('TEAMSPEAK_URI') . '/' . env('TEAMSPEAK_SERVER') . '/channellist', [
                'headers' => [
                    'x-api-key' => env('TEAMSPEAK_SECRET')
                ],
                'query' => []
            ]);
            $data = \GuzzleHttp\json_decode($result->getBody()->getContents());

            $channel = $data->body[rand(0, count($data->body) - 1)];


            $result = $client->get(env('TEAMSPEAK_URI') . '/' . env('TEAMSPEAK_SERVER') . '/clientlist', [
                'headers' => [
                    'x-api-key' => env('TEAMSPEAK_SECRET')
                ]
            ]);
            $data = \GuzzleHttp\json_decode($result->getBody()->getContents());

            $clid = -1;

            foreach($data->body as $tsClient) {
                if($tsClient->client_database_id == $target) {
                    $clid = $tsClient->clid;
                    break;
                }
            }


            if($clid == -1) {
                return redirect('/');
            };

            $result = $client->get(env('TEAMSPEAK_URI') . '/' . env('TEAMSPEAK_SERVER') . '/clientmove', [
                'headers' => [
                    'x-api-key' => env('TEAMSPEAK_SECRET')
                ],
                'query' => [
                    'clid' => $clid,
                    'cid' => $channel->cid
                ]
            ]);
            $data = \GuzzleHttp\json_decode($result->getBody()->getContents());

            return redirect('/');
        });
    }
});

