<?php

namespace App\Http\Controllers\Admin;

use App\Models\Texture;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class TextureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_unless(Gate::allows('admin-rank-3'), 403);

        $limit = 50;

        if(request()->has('limit')) {
            if (request()->get('limit') < 0) {
                $limit = 1;
            } else if (request()->get('limit') > 500) {
                $limit = 500;
            }
            $limit = request()->get('limit');
        }

        $textures = Texture::query()->with(['admin', 'user']);

        $textures->orderBy('Id', 'DESC');

        $textures = $textures->paginate($limit);

        return view('admin.textures.index', ['textures' => $textures, 'limit' => $limit]);
    }
}
