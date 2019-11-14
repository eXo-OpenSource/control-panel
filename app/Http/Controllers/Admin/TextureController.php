<?php

namespace App\Http\Controllers\Admin;

use App\Texture;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TextureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $limit = 50;

        if(request()->has('limit')) {
            if (request()->get('limit') < 0) {
                $limit = 1;
            } else if (request()->get('limit') > 500) {
                $limit = 500;
            }
            $limit = request()->get('limit');
        }

        $textures = Texture::query();

        $textures->orderBy('Id', 'DESC');

        $textures = $textures->paginate($limit);

        return view('admin.textures.index', ['textures' => $textures, 'limit' => $limit]);
    }
}
