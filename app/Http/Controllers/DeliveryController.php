<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Project;
use App\Models\District;
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
            'projects.name as text', 
        )
        ->whereIn('city_id', $cities_id_list)->get();

        return response()->json(['data' => $projects], 200);
    }
    public function gather_beneficiaries($id){
        return response()->json(['data' => Beneficiary::select(
        'beneficiaries.id', 
        'beneficiaries.name as text',
        )->where('project_id',$id)->get()],200);
    }
    public function store(Request $request){
        dd($request);
    }
}
