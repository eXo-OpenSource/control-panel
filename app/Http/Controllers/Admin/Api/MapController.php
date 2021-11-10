<?php


namespace App\Http\Controllers\Admin\Api;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\MapEditorMap;
use App\Models\MapEditorObject;

class MapController extends Controller
{

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function update(Request $request, int $mapId)
    {
        abort_unless(Gate::allows('admin-rank-7'), 403);

        $type = $request->get('type');

        if ($type === 'map-copy') {
            if (Gate::allows('admin-rank-7')) {
                $fromMap = $mapId;
                $toMap = $request->get('toMap');
                $fromConnection = $request->get('connection');
                $toConnection;

                $env = MapEditorMap::on($fromConnection);
                $env_map = $env->find($fromMap);

                switch($fromConnection){
                    case 'mysql':
                        $toConnection = 'mysql_test';
                    case 'mysql_test':
                        $toConnection = 'mysql';
                }

                //If there is no map to overwrite, create a new one, else delete objects of specified map
                if (empty($toMap)) {
                    $map = new MapEditorMap();
                    $map->setConnection($toConnection);
                    $map->Name = $env_map->Name;
                    $map->Creator = $env_map->Creator;
                    $map->SaveObjects = $env_map->SaveObjects;
                    $map->Activated = $env_map->Activated;
                    $map->Deactivatable = $env_map->Deactivatable;
                    $map->save();

                    $toMap = $map->Id;
                } else {
                    $map = MapEditorMap::on($toConnection)->find($toMap);
                    if (!$map) {
                        return ['status' => 'Error', 'message' => __('Es existiert keine Map mit der Id :Id', ['Id' => $toMap])];
                    }

                    $objects = MapEditorObject::on($toConnection);
                    $res = $objects->where('MapId', $toMap)->delete();
                }

                $objects = MapEditorObject::on($fromConnection)->where('MapId', $fromMap)->get();

                foreach($objects as $object) {
                    $mapObject = new MapEditorObject();
                    $mapObject->setConnection($toConnection);
                    $mapObject->Creator = $object->Creator;
                    $mapObject->MapId = $toMap;
                    $mapObject->Type = $object->Type;
                    $mapObject->Model = $object->Model;
                    $mapObject->PosX = $object->PosX;
                    $mapObject->PosY = $object->PosY;
                    $mapObject->PosZ = $object->PosZ;
                    $mapObject->RotX = $object->RotX;
                    $mapObject->RotY = $object->RotY;
                    $mapObject->RotZ = $object->RotZ;
                    $mapObject->Dimension = $object->Dimension;
                    $mapObject->Interior = $object->Interior;
                    $mapObject->ScaleX = $object->ScaleX;
                    $mapObject->ScaleY = $object->ScaleY;
                    $mapObject->ScaleZ = $object->ScaleZ;
                    $mapObject->Collision = $object->Collision;
                    $mapObject->Breakable = $object->Breakable;
                    $mapObject->Doublesided = $object->Doublesided;
                    $mapObject->save();
                }

                return ['status' => 'Success', 'message' => __('Die Map wurde erfolgreich kopiert.')];
            }
        }
    }
}