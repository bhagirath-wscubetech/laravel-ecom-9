<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     *  To open the login page
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.login');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $request->validate(
            [
                'email' => 'required|email',
                'password' => 'required'
            ]
        );
        $admin = Admin::where('admin_email', $request->email)->first();
        if (!is_null($admin)) {
            if (Hash::check($request->password, $admin->admin_password)) {
                // pass
                session([
                    'admin_id' => $admin->admin_id,
                    'admin_name' => $admin->admin_name,
                    'admin_role' => $admin->admin_role
                ]);
                return redirect('/admin');
            } else {
                // fail
                return redirect()->back()->withErrors('Invalid credentails')->withInput();
            }
        } else {
            return redirect()->back()->withErrors('Invalid credentails')->withInput();
        }
    }

    public function register()
    {
        return view('admin.register');
    }

    public function checkEmail($email)
    {
        $admin = Admin::where('admin_email', $email)->first();
        if (is_null($admin)) {
            return response()->json([
                'status' => 1
            ]);
        } else {
            return response()->json([
                'status' => 0
            ]);
        }
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $admin = new Admin();
            $admin->admin_name = $request->name;
            $admin->admin_email = $request->email;
            $admin->admin_password = Hash::make($request->password);
            $admin->save();
            DB::commit();   
        } catch (\Exception $err) {
            DB::rollBack();
            $admin = null;
        }
        if (is_null($admin)) {
            return redirect()->back()->withErrors("Unable to register admin")->withInput();
        } else {
            // email send
            return redirect('/admin/get-admins');
        }
    }

    public function getAdmins()
    {
        $admins = Admin::get();
        p($admins->toArray());
    }
}
