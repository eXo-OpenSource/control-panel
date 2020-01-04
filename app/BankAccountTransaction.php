<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BankAccountTransaction extends Model
{
    protected $primaryKey = 'Id';
    protected $connection = 'mysql_logs';
    protected $table = 'MoneyNew';

    public function getFromName()
    {
        if ($this->FromType === 1) {
            return User::find($this->FromId)->Name;
        } else if ($this->FromType === 2) {
            return Faction::find($this->FromId)->Name;
        } else if ($this->FromType === 3) {
            return Company::find($this->FromId)->Name;
        } else if ($this->FromType === 4) {
            return 'Adminkasse';
        } else if ($this->FromType === 5) {
            return 'Serverkasse: ' . ServerBankAccount::find($this->FromId)->Name;
        } else if ($this->FromType === 6) {
            return 'Shop: ' . Shop::find($this->FromId)->Name;
        } else if ($this->FromType === 7) {
            return 'House ID: ' . $this->FromId;
        } else if ($this->FromType === 8) {
            return Group::find($this->FromId)->Name;
        } else if ($this->FromType === 9) {
            return 'Fahrzeugshop: ' . VehicleShop::find($this->FromId)->Name;
        }

        return 'Unknown';
    }

    public function getToName()
    {
        if ($this->ToType === 1) {
            return User::find($this->ToId)->Name;
        } else if ($this->ToType === 2) {
            return Faction::find($this->ToId)->Name;
        } else if ($this->ToType === 3) {
            return Company::find($this->ToId)->Name;
        } else if ($this->ToType === 4) {
            return 'Adminkasse';
        } else if ($this->ToType === 5) {
            return 'Serverkasse: ' . ServerBankAccount::find($this->ToId)->Name;
        } else if ($this->ToType === 6) {
            return 'Shop: ' . Shop::find($this->ToId)->Name;
        } else if ($this->ToType === 7) {
            return 'House ID: ' . $this->ToId;
        } else if ($this->ToType === 8) {
            return Group::find($this->ToId)->Name;
        } else if ($this->ToType === 9) {
            return 'Fahrzeugshop: ' .VehicleShop::find($this->ToId)->Name;
        }

        return 'Unknown';
    }

    public function toArray()
    {
        $result = [
            'Id' => $this->Id,
            'From' => $this->getFromName(),
            'FromId' => $this->FromId,
            'FromType' => $this->FromType,
            'To' => $this->getToName(),
            'ToId' => $this->ToId,
            'ToType' => $this->ToType,
            'Amount' => $this->Amount,
            'Reason' => $this->Reason,
            'Date' => $this->Date,
        ];

        if ($this->FromType === 1) {
            $result['FromForumId'] = User::find($this->FromId)->ForumID;
        }

        if ($this->ToType === 1) {
            $result['ToForumId'] = User::find($this->ToId)->ForumID;
        }

        return $result;
    }
}
