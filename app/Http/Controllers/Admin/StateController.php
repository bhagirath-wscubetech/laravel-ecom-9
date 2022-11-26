<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        setMenuStatus("state", "view");
        $states = State::with('country')->get();
        $title = "State - View";
        $data = compact('title', 'states');
        return view("admin.state.view")->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        setMenuStatus("state", "create");
        $title = "State - Create";
        $data = compact('title');
        return view("admin.state.add")->with($data);
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
                    'unique:states,states.state_name'
                ],
                'country_id' => 'required|gt:0'
            ],
            [
                'name.required' => "The state name cannot be empty",
                'country_id.gt' => 'Please select a category'
            ]
        );

        // ----------------
        DB::beginTransaction();
        try {
            // insert
            $state = new State();
            $state->state_name = $request['name'];
            $state->country_id = $request['country_id'];
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
            return redirect()->route('admin.state.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\State  $state
     * @return \Illuminate\Http\Response
     */
    public function show(State $state)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\State  $state
     * @return \Illuminate\Http\Response
     */
    public function edit(State $state)
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
    public function update(Request $request, State $state)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\State  $state
     * @return \Illuminate\Http\Response
     */
    public function destroy(State $state)
    {
        //
    }

    public function getStates($country)
    {
        $states = State::where('country_id', $country)->get();
        if (count($states) == 0) {
            return response()->json([
                'status' => 0,
                'data' => []
            ]);
        } else {
            return response()->json([
                'status' => 1,
                'data' => $states->toArray()
            ]);
        }
    }
}
