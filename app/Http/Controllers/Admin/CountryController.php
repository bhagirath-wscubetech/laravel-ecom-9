<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        setMenuStatus("country", "view");
        $countries = Country::get();
        $title = "Country - View";
        $data = compact('title', 'countries');
        return view("admin.country.view")->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        setMenuStatus("country", "create");
        $title = "Country - Create";
        $data = compact('title');
        return view("admin.country.add")->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => [
                    'required',
                    'max:100',
                    'unique:countries,countries.country_name'
                ]
            ],
            [
                'name.required' => "The category name cannot be empty",
            ]
        );

        // ----------------
        DB::beginTransaction();
        try {
            // insert
            $country = new Country();
            $country->country_name = $request['name'];
            $country->save();
            DB::commit();
        } catch (\Exception $err) {
            $country = null;
            DB::rollBack();
        }
        if (is_null($country)) {
            // error aai hai
            return redirect()->back()->withInput()->withErrors("Unable to add country. Internal server error");
        } else {
            return redirect()->route('admin.country.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function show(Country $country)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function edit(Country $country)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Country $country)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function destroy(Country $country)
    {
        //
    }

    public function checkCountryName($name, $id = null)
    {
        if (is_null($id)) {
            $data = Country::where('country_name', $name)->first();
        } else {
            $data = Country::where('country_name', $name)->where('id', "!=", $id)->first();
        }
        if (is_null($data)) {
            // test pass
            return response()->json(['status' => 1]);
        } else {
            // test fail
            return response()->json(['status' => 0]);
        }
    }
}
