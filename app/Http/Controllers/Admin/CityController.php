<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        setMenuStatus("city", "view");
        // $cities = City::with(
        //     [
        //         'state' => function ($qry) {
        //             $qry->join('countries', function ($join) {
        //                 $join->on('states.country_id', "=", 'countries.country_id');
        //             });
        //         }
        //     ]
        // )->get();
        $cities = City::with('state')->get();
        $title = "State - View";
        $data = compact('title', 'cities');
        return view("admin.city.view")->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        setMenuStatus("city", "create");
        $countries = Country::where('country_status', 1)->get();
        $title = "City - Create";
        $data = compact('title', 'countries');
        return view("admin.city.add")->with($data);
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
                    'max:100'
                ],
                'country' => 'required|gt:0',
                'state' => 'required|gt:0'
            ],
            [
                'name.required' => "The city name cannot be empty",
                'country.gt' => 'Please select a country',
                'state.gt' => 'Please select a state'
            ]
        );

        // ----------------
        DB::beginTransaction();
        try {
            // insert
            $state = new City();
            $state->city_name = $request['name'];
            $state->state_id = $request['state'];
            $state->save();
            DB::commit();
        } catch (\Exception $err) {
            $state = null;
            DB::rollBack();
        }
        if (is_null($state)) {
            // error aai hai
            return redirect()->back()->withInput()->withErrors("Unable to add state. Internal server error");
        } else {
            return redirect()->route('admin.city.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\State  $state
     * @return \Illuminate\Http\Response
     */
    public function show(City $state)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\State  $state
     * @return \Illuminate\Http\Response
     */
    public function edit(City $state)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\State  $state
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, City $state)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\State  $state
     * @return \Illuminate\Http\Response
     */
    public function destroy(City $state)
    {
        //
    }
}
