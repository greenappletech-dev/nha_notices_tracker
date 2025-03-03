<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use Illuminate\Http\Request;

class DeliveryHistoryController extends Controller
{
    //
    public function index()
    {
        $deliveries = Delivery::select(
            'deliveries.id',
            'deliveries.photo',
            'demand_notice_trackers.name as notice_type_name',
            'projects.name as project_name',
            'beneficiaries.name as beneficiary_name',
            'beneficiaries.address',
            'deliveries.date_captured',
            'users.name as collector_name'
        )
        ->leftJoin('demand_notice_trackers', 'deliveries.notice_type_id', 'demand_notice_trackers.id')
        ->leftJoin('projects', 'projects.id', 'deliveries.project_id')
        ->leftJoin('beneficiaries', 'beneficiaries.id', 'deliveries.beneficiary_id')
        ->leftJoin('users', 'users.id', 'deliveries.user_id')
        ->get();
    
        return view('deliveryhistory', compact('deliveries'));
    }
    
}
