<?php

namespace App\Http\Controllers\Api;

use App\Models\TicketCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TicketCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return TicketCategory::all();
    }
}
