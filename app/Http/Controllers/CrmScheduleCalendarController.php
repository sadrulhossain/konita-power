<?php

namespace App\Http\Controllers;

use App\CrmOpportunity;
use App\CrmActivityLog;
use App\CrmActivityStatus;
use App\User;
use App\Buyer;
use App\Product;
use App\Brand;
use App\Grade;
use DB;
use Auth;
use Response;
use Input;
use Helper;
use Illuminate\Http\Request;

class CrmScheduleCalendarController extends Controller {

    public function index(Request $request) {
        //Find Schedule of All Opportunity
        $logArrPre = CrmActivityLog::leftJoin('crm_opportunity_to_member', 'crm_opportunity_to_member.opportunity_id', 'crm_activity_log.opportunity_id')
                ->join('users', 'users.id', 'crm_opportunity_to_member.member_id')
                ->select('crm_activity_log.opportunity_id', 'crm_activity_log.log'
                        , DB::raw("CONCAT(users.first_name, ' ', users.last_name) as member_name"));
        if (Auth::user()->group_id != '1' && Auth::user()->for_crm_leader != '1') {
            $logArrPre = $logArrPre->where('crm_opportunity_to_member.member_id', Auth::user()->id);
        }
        $logArrPre = $logArrPre->get();

        $opportunityWithBuyerNameArr = CrmOpportunity::where('buyer_has_id', '0')->pluck('buyer', 'id')->toArray();
        $opportunityWithBuyerIdArr = CrmOpportunity::join('buyer', 'buyer.id', 'crm_opportunity.buyer')
                        ->where('crm_opportunity.buyer_has_id', '1')->pluck('buyer.name', 'crm_opportunity.id')->toArray();
        $opportunityArr = $opportunityWithBuyerNameArr + $opportunityWithBuyerIdArr;
        $statusColorCodeArr = CrmActivityStatus::pluck('color_code', 'id')->toArray();
        $statusColorArr = CrmActivityStatus::pluck('color', 'id')->toArray();
        $statusArr = CrmActivityStatus::pluck('name', 'id')->toArray();

        $order = array("\r\n", "\n", "\r");
        $replace = '<br />';

        $activityEventArr = [];
        if (!$logArrPre->isEmpty()) {
            foreach ($logArrPre as $log) {
                $activityLogArr = json_decode($log->log, true);

                if (!empty($activityLogArr)) {
                    foreach ($activityLogArr as $key => $item) {
                        if ($item['has_schedule'] == '1') {
                            $activityEventArr[$key]['opportunity_id'] = $log->opportunity_id;
                            $activityEventArr[$key]['schedule_creator'] = $log->member_name;
                            $activityEventArr[$key]['title'] = date("D, d M Y", strtotime($item['schedule_date_time']));
                            $activityEventArr[$key]['start_date'] = $item['schedule_date_time'];
                            $activityEventArr[$key]['purpose'] = str_replace($order, $replace, $item['schedule_purpose']);
                            $activityEventArr[$key]['color'] = (!empty($item['status']) && isset($statusColorArr[$item['status']])) ? $statusColorArr[$item['status']] : '';
                            $activityEventArr[$key]['color_code'] = (!empty($item['status']) && isset($statusColorCodeArr[$item['status']])) ? $statusColorCodeArr[$item['status']] : '';
                            $activityEventArr[$key]['status'] = (!empty($item['status']) && isset($statusArr[$item['status']])) ? $statusArr[$item['status']] : '';;
                            $activityEventArr[$key]['schedule_status'] = $item['schedule_status'] ?? '';
                            $activityEventArr[$key]['schedule_done_color'] = $statusColorCodeArr[12] ?? '';
                        }
                    }//foreach
                }
            }//foreach
        }//if

        return view('crmScheduleCalendar.index')->with(compact('activityEventArr', 'opportunityArr'));
    }
    
    
    public function scheduleDone(Request $request) {
        $opportunityId = $request->opportunity_id;
        $activityKey = $request->activity_key;
        
        $CrmActivityInfo = CrmActivityLog::where('opportunity_id',$opportunityId)->first();
        $keyArr = [];
        if(!empty($CrmActivityInfo)){
            $activityLog = json_decode($CrmActivityInfo->log,TRUE);
            
            if(!empty($activityLog)) {
                foreach($activityLog as $key => $logItem) {
                    if($key == $activityKey){
                        $activityLog[$key]['schedule_status'] = "1";
                    }
                }
                
            }
            
        }
        
        $log = json_encode($activityLog);
        $data = [
            'log' => $log,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => Auth::user()->id
        ];
        
        $doneColor = "#F2784B";
        
        
        
        if (CrmActivityLog::where('opportunity_id',$opportunityId)->update($data)) {
            return Response::json(array('heading' => 'Success','color' => $doneColor, 'message' => __('label.SCHEDULE_DONE_SUCCESSFULLY')), 200);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.SCHEDULE_NOT_DONE')), 401);
        }
        
    }

}
