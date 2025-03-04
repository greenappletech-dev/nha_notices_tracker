<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Support\Facades\File;
use App\Models\Project;
use App\Models\District;
use App\Models\Delivery;
use App\Models\Province;
use App\Models\Beneficiary;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    //
    public function index(){
        $districts = District::select(
            'districts.id', 
            'districts.name', 
        )->get();
        return view('deliveries', compact('districts'));
    }
    public function store(Request $request){
        $request->validate([
            'notice_id' => 'required',
            'project_id' => 'required',
            'beneficiary_id' => 'required',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        //pag wala pang folder na uploads/deliveries, gagawin nya
        $folderPath = public_path('uploads/deliveries');

        // ccheck kung exist na
        if (!File::exists($folderPath)) {
            File::makeDirectory($folderPath, 0755, true, true);
        }

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            //rekta save sa uploads/deliveries
            $file->move($folderPath, $filename);
            $photoPath = 'uploads/deliveries/' . $filename;
            // dd($photoPath);
        }

        $delivery = Delivery::create([
            'notice_type_id' => $request->notice_id,
            'project_id' => $request->project_id,
            'beneficiary_id' => $request->beneficiary_id,
            'photo' => $photoPath,
            'date_captured' => now(),
            'user_id' => auth()->user()->id
        ]);

        return response()->json([
            'message' => 'Delivery saved successfully!',
            'delivery' => $delivery
        ], 201);
    }

    public function gather_project($id){
        $cities_id_list = [];
        $provinces = Province::where('district_id', $id)->get();
        for($i = 0; $i < count($provinces); $i++){
            $cities = City::where('province_id', $provinces[$i]->id)->get();
            foreach($cities as $city){
                $cities_id_list[] = $city->id;
            }
        }
        $projects = Project::select(
            'projects.id', 
            'projects.name as text' 
        )
        ->whereIn('city_id', $cities_id_list)->get();

        return response()->json(['data' => $projects], 200);
    }
    public function gather_beneficiaries($id){
        $dataArr = [];
        $current_year = date('Y');
        $current_month = date('m');
        $delivery_list = Delivery::whereMonth('date_captured', $current_month)->whereYear('date_captured', $current_year)->where('project_id', $id)->get();
        foreach($delivery_list as $delivery){
            $dataArr[] = $delivery->beneficiary_id;
        }
        // dd($dataArr);
        return response()->json(['data' => Beneficiary::select(
        'beneficiaries.id', 
        'beneficiaries.name as text',   
        'beneficiaries.address',
        'beneficiaries.com_code',
        )->whereNotIn('beneficiaries.id',$dataArr)->where('project_id',$id)->orderBy('com_code')->get()],200);
    }

}
