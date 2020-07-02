<?php

namespace App\Models;

use App\Services\ForumService;
use App\Services\MTAService;
use Carbon\Carbon;
use App\Models\Logs\Chat;
use App\Models\Logs\Heal;
use App\Models\Logs\Kills;
use App\Models\Logs\Login;
use App\Models\Logs\Advert;
use App\Models\Logs\Damage;
use App\Models\Logs\Punish;
use App\Models\Shop\PremiumUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Notifications\Notifiable;
use App\Http\Controllers\WhoIsOnlineController;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Lab404\Impersonate\Models\Impersonate;

class User extends Authenticatable
{
    use Notifiable;
    use Impersonate;

    protected $table = 'account';
    protected $primaryKey = 'Id';
    protected $connection = 'mysql';
    protected $dates = ['LastLogin', 'RegisterDate'];
    public $timestamps = false;

    // ALTER TABLE `vrp_account` ADD COLUMN `RememberToken` varchar(100) NULL AFTER `AutologinToken`;
    protected $rememberTokenName = 'RememberToken';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function character()
    {
        return $this->hasOne(Character::class, 'Id', 'Id');
    }

    public function textures()
    {
        return $this->hasMany(Texture::class, 'UserId', 'Id');
    }

    public function teamSpeakIdentities()
    {
        return $this->hasMany(TeamSpeakIdentity::class, 'UserId', 'Id');
    }

    public function warns()
    {
        return $this->hasMany(Warn::class, 'userId', 'Id');
    }

    public function punish()
    {
        return $this->hasMany(Punish::class, 'UserId', 'Id');
    }

    public function kills()
    {
        return $this->hasMany(Kills::class, 'UserId', 'Id');
    }

    public function deaths()
    {
        return $this->hasMany(Kills::class, 'TargetId', 'Id');
    }

    public function heal()
    {
        return $this->hasMany(Heal::class, 'UserId', 'Id');
    }

    public function logins()
    {
        return $this->hasMany(Login::class, 'UserId', 'Id');
    }

    public function advert()
    {
        return $this->hasMany(Advert::class, 'UserId', 'Id');
    }

    public function hardware()
    {
        return $this->hasMany(ClientStatistic::class, 'UserId', 'Id');
    }

    public function serials()
    {
        return $this->hasMany(AccountSerial::class, 'PlayerId', 'Id');
    }

    public function screenshots()
    {
        return $this->hasMany(AccountScreenshot::class, 'UserId', 'Id');
    }

    public function damage()
    {
        return Damage::query()->where('UserId', $this->Id)->orWhere('TargetId', $this->Id); // $this->hasMany(Damage::class, 'UserId', 'Id');
    }

    public function money()
    {
        return BankAccountTransaction::query()->where('FromType', 1)->where('FromId', $this->Id)->orWhere('ToType', 1)->where('ToId', $this->Id); // $this->hasMany(Damage::class, 'UserId', 'Id');
    }

    public function chat()
    {
        return $this->belongsToMany(Chat::class, 'ChatReceivers', 'Receiver', 'MessageId', 'Id', 'ID');
    }

    public function bans()
    {
        return $this->hasMany(Ban::class, 'player_id', 'Id');
    }

    public function teamSpeakBan()
    {
        return $this->hasMany(TeamSpeakBan::class, 'UserId', 'Id');
    }

    public function isBanned()
    {
        $time = (new \DateTime())->getTimestamp();

        foreach($this->bans as $ban) {
            if($ban->expires >= $time || $ban->expires === 0) {
                return $ban->expires;
            }
        }

            // DB::table('warns')->where('userId', $this->Id)->where('expires', '>=', $time)->orderBy('expires', 'DESC')->limit(3)->get();

        $activeWarns = 0;
        $warns = [];

        foreach($this->warns as $warn)
        {
            if($warn->expires >= $time) {
                array_push($warns, $warn->expires);
                $activeWarns++;
            }
        }

        if ($activeWarns >= 3) {
            rsort($warns);
            return $warns[2];
        }
        return false;
    }

    public function isTeamSpeakBanned()
    {
        $banDuration = -1;

        foreach($this->teamSpeakBan as $ban) {
            if($ban->Duration === 0) {
                $banDuration = 0;
                break;
            }

            if($ban->ValidUntil < Carbon::now()) {
                $ban->delete();
            } else {
                $duration = $ban->ValidUntil->diffInSeconds(Carbon::now());
                if($banDuration < $duration) {
                    $banDuration = $duration;
                }
            }
        }

        return $banDuration >= 0 ?? false;
    }

    public function isOnline() {

        $data = WhoIsOnlineController::getOnlinePlayers();
        $online = false;
        foreach($data->Players as $player) {
            if ($player->Id == $this->Id) {
                $online = true;
                break;
            }
        }
        return $online;
    }

    public function getActivity(Carbon $from, Carbon $to)
    {
        return AccountActivity::getActivity($this, $from, $to);
    }

    public function getMods()
    {
        $serials = $this->serials->pluck('Serial');

        return AccountMod::query()->whereIn('Serial', $serials)->orderBy('LastSeenAt', 'DESC')->get();
    }

    public function bank()
    {
        return $this->morphOne(BankAccount::class, 'bank', 'OwnerType', 'OwnerId', 'Id');
    }

    public function getMorphClass()
    {
        return 1;
    }

    public function getName()
    {
        return $this->Name;
    }

    public function getURL()
    {
        return route('users.show', $this->Id);
    }

    public function tickets()
    {
        return $this->belongsToMany(Ticket::class, 'ticket_users', 'UserId', 'TicketId')->withPivot('JoinedAt', 'LeftAt');
    }

    public function premium()
    {
        return $this->hasOne(PremiumUser::class, 'UserId', 'Id');
    }

    public function sendMessage($message, $color, $url)
    {
        $result = json_decode(resolve(MTAService::class)->sendMessage('player', $this->Id, $message, ['r' => $color['r'], 'g' => $color['g'], 'b' => $color['b'], 'offline' => true])[0]);

        if($result->status !== 'SUCCESS' || !$result->online)
        {
            resolve(ForumService::class)->sendNotification($this->ForumID, 'Ticketsystem', $message, $url);
        }
    }
}
