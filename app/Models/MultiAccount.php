<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class MultiAccount extends Model
{
    protected $primaryKey = 'ID';
    protected $table = 'account_multiaccount';

    public function admin() {
        return $this->hasOne(User::class, 'Id', 'Admin');
    }

    public function getUsers() {
        $users = [];
        $ids = json_decode($this->LinkedTo, true)[0];
        if (is_array($ids)) {
            foreach ($ids as $id) {
                $users[] = User::find($id);
            }
        }
        return $users;
    }
}
