<?php

namespace App\Http\Controllers;



class HomeController extends Controller
{
    /**
     * สร้าง controller instance ใหม่
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * แสดงหน้าหลักของแอพพลิเคชั่น
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('index');
    }
}
