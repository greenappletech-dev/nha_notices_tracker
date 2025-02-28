<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DemandNotice;

class DemandNoticeController extends Controller
{
    public function index()
    {
        return view('masterdata.demandnotice');
    }

    public function show()
    {
        $data = demand_notice::all();
        return response()->json(['data' => $data], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $demand_notices = demand_notice::create([
            'name' => $request->name,
        ]);

        return response()->json([
            'message' => 'Demand Notice successfully created!',
            'data' => $demandNotice,
        ], 201);
    }

    public function edit($id)
    {
        $demand_notices = demand_notice::find($id);
        return response()->json(['demand_notice' => $demand_notices], 200);
    }

    public function update(Request $request, $id)
    {
        // dd($request);
        $request->validate([
            'name' => 'required|unique:regions,name,' . $id,
        ], [], [
            'name' => 'Demand Notice Name',
        ]);

        $data = demand_notice::where('id', $id)->first();
        $data->name = $request->name;
        $data->save();

        return response()->json(['message' => 'Demand Notice updated successfully!']);
    }

    public function destroy($id)
    {
        $data = demand_notice::where('id', $id)->first();
        $data->delete();

        return response()->json(['message' => 'Demand Notice deleted successfully!']);
    }
}
