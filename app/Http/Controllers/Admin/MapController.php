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

class MapController extends Controller
{
    public function index()
    {
        abort_unless(Gate::allows('admin-rank-5'), 403);
        $maps = MapEditorMap::all();
        return view('admin.maps.index', compact('maps'));
    }

    public function create()
    {
        abort_unless(Gate::allows('admin-rank-7'), 403);
        return view('admin.maps.create');
    }

    public function store(Request $request)
    {
        abort_unless(Gate::allows('admin-rank-7'), 403);
        $data = $request->validate([
            'name' => 'required|unique:map_editor_maps,Name',
            'map' => 'required|file'
        ]);

        /** @var UploadedFile $mapFile */
        $mapFile = $data['map'];

        $xml = XmlParser::load($mapFile->getPathname());

        $objects = [];

        foreach($xml->getContent() as $key => $entry) {
            $object = [
                'Model' => intval($entry->attributes()->model),
                'PosX' => floatval($entry->attributes()->posX),
                'PosY' => floatval($entry->attributes()->posY),
                'PosZ' => floatval($entry->attributes()->posZ),
                'RotX' => floatval($entry->attributes()->rotX),
                'RotY' => floatval($entry->attributes()->rotY),
                'RotZ' => floatval($entry->attributes()->rotZ),
                'Dimension' => intval($entry->attributes()->dimension),
                'Interior' => intval($entry->attributes()->interior),
                'ScaleX' => floatval($entry->attributes()->scale),
                'ScaleY' => floatval($entry->attributes()->scale),
                'ScaleZ' => floatval($entry->attributes()->scale),
                'Collision' => $entry->attributes()->collisions == "true" ? 1 : 0,
                'Breakable' => $entry->attributes()->breakable == "true" ? 1 : 0,
                'Doublesided' => $entry->attributes()->doublesided == "true" ? 1 : 0,
            ];

            if($key === 'removeWorldObject') {
                $object['Type'] = 0;
                array_push($objects, $object);
            } elseif ($key === 'object') {
                $object['Type'] = 1;
                array_push($objects, $object);
            }
        }

        $map = new MapEditorMap();
        $map->Name = $data['name'];
        $map->Creator = auth()->user()->Id;
        $map->SaveObjects = 1;
        $map->Activated = 0;
        $map->Deactivatable = 1;
        $map->save();

        foreach($objects as $object) {
            $mapObject = new MapEditorObject();
            $mapObject->Creator = auth()->user()->Id;
            $mapObject->MapId = $map->Id;
            $mapObject->Type = $object['Type'];
            $mapObject->Model = $object['Model'];
            $mapObject->PosX = $object['PosX'];
            $mapObject->PosY = $object['PosY'];
            $mapObject->PosZ = $object['PosZ'];
            $mapObject->RotX = $object['RotX'];
            $mapObject->RotY = $object['RotY'];
            $mapObject->RotZ = $object['RotZ'];
            $mapObject->Dimension = $object['Dimension'];
            $mapObject->Interior = $object['Interior'];
            $mapObject->ScaleX = $object['ScaleX'];
            $mapObject->ScaleY = $object['ScaleY'];
            $mapObject->ScaleZ = $object['ScaleZ'];
            $mapObject->Collision = $object['Collision'];
            $mapObject->Breakable = $object['Breakable'];
            $mapObject->Doublesided = $object['Doublesided'];
            $mapObject->save();
        }

        Session::flash('alert-success', 'Erfolgreich hochgeladen!');
        return redirect()->route('admin.maps.index');
    }
}
