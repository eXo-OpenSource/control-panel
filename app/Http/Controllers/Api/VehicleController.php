<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\Vehicle;

class VehicleController extends Controller
{
    public function show(Vehicle $vehicle)
    {
        abort_unless(auth()->user()->can('show', $vehicle), 403);

        $data = $vehicle->toArray();

        unset($data['OldId']);
        unset($data['OldTable']);
        unset($data['TrunkId']);
        unset($data['RotX']);
        unset($data['RotY']);
        unset($data['RotZ']);
        unset($data['Interior']);
        unset($data['Dimension']);
        unset($data['PositionType']);
        unset($data['Health']);
        unset($data['Fuel']);
        unset($data['SalePrice']);
        unset($data['BuyPrice']);
        unset($data['Handling']);
        unset($data['ELSPreset']);
        unset($data['ShopIndex']);
        unset($data['TemplateId']);

        if(!auth()->user()->can('private', $vehicle)) {
            unset($data['PosX']);
            unset($data['PosY']);
            unset($data['PosZ']);
            unset($data['LastUsed']);
            unset($data['Keys']);
            unset($data['Deleted']);
        }

        if($data['Tunings'] && $data['Tunings'][0]) {
            $data['Tunings'] = json_decode($data['Tunings'])[0];
        } else {
            $data['Tunings'] = json_decode($data['Tunings']);
        }

        return $data;
    }
}
