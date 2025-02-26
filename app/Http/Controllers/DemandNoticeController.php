<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DemandNotice; // Ensure this model exists

class DemandNoticeController extends Controller
{
    // Show the demand notice page
    public function index()
    {
        return view('masterdata.demandnotice');
    }

    // Fetch all demand notices for Vue table
    public function show()
    {
        $demandNotices = DemandNotice::all();
        return response()->json(['data' => $demandNotices]);
    }

    // Store a new record
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $demandNotice = DemandNotice::create([
            'name' => $request->name,
        ]);

        return response()->json([
            'message' => 'Demand Notice successfully created!',
            'data' => $demandNotice,
        ], 201);
    }

    // Update an existing record
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $demandNotice = DemandNotice::findOrFail($id);
        $demandNotice->update([
            'name' => $request->name,
        ]);

        return response()->json([
            'message' => 'Demand Notice successfully updated!',
            'data' => $demandNotice,
        ]);
    }

    // Delete a record
    public function destroy($id)
    {
        $demandNotice = DemandNotice::findOrFail($id);
        $demandNotice->delete();

        return response()->json([
            'message' => 'Demand Notice successfully deleted!',
        ]);
    }
}
