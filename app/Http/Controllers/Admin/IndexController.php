<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function dashboard(){
        // session(["menu" => "dashboard", "sub-menu" => ""]);
        setMenuStatus("dashboard","");
        $title = "Admin - Dashboard";
        $data = compact('title');
        return view('admin.dashboard')->with($data);
    }
}
