<?php

namespace App\Http\Controllers;

use Validator;
use App\DbBackupDownLoadLog;
use App\CompanyInformation;
use Session;
use Redirect;
use Auth;
use Common;
use Input;
use Helper;
use File;
use Response;
use DB;
use PDF;
use Illuminate\Http\Request;

class DbBackupDownloadLogReportController extends Controller {

    public function index(Request $request) {
        $qpArr = $request->all();
        $filedata = array();
        $logInfo = [];
        //KONITA INFO
        $konitaInfo = CompanyInformation::first();
        $phoneNumber = '';

        $fromDate = $toDate = '';
        
        if ( $request->generate == 'true') {

            $fromDate = !empty($request->from_date) ? Helper::dateFormatConvert($request->from_date) . ' 00:00:00' : '';
            $toDate = !empty($request->to_date) ? Helper::dateFormatConvert($request->to_date) . ' 23:59:59' : '';
            
            $logInfo = DbBackupDownLoadLog::join('users', 'users.id', 'db_backup_download_log.user_id')
                    ->select(DB::raw("CONCAT(users.first_name, ' ', users.last_name, ' (', users.employee_id,')') as user")
                            , 'db_backup_download_log.log_time', 'db_backup_download_log.downloaded_file')
                    ->whereBetween('db_backup_download_log.log_time', [$fromDate, $toDate])
                    ->orderBy('db_backup_download_log.log_time', 'asc')
                    ->get();
            if (!empty($konitaInfo)) {
                $phoneNumberDecode = json_decode($konitaInfo->phone_number, true);
                $phoneNumber = Helper::arrayTostring($phoneNumberDecode);
            }
        }

        $userAccessArr = Common::userAccess();
        if ($request->view == 'print') {
            if (empty($userAccessArr[86][6])) {
                return redirect('dashboard');
            }
            return view('report.dbBackupDownloadLog.print.index')->with(compact('request', 'logInfo', 'qpArr', 'konitaInfo', 'phoneNumber'));
        } else {
            return view('report.dbBackupDownloadLog.index')->with(compact('request', 'logInfo', 'qpArr', 'konitaInfo', 'phoneNumber'));
        }
    }

    public
            function filter(Request $request) {
//        $messages = [];
        $rules = [
            'from_date' => 'required',
            'to_date' => 'required',
        ];

        $messages = [
            'from_date.required' => __('label.THE_FROM_DATE_FIELD_IS_REQUIRED'),
            'to_date.required' => __('label.THE_TO_DATE_FIELD_IS_REQUIRED'),
        ];
        $url = 'from_date=' . $request->from_date . '&to_date=' . $request->to_date;
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect('dbBackupDownloadLogReport?generate=false&' . $url)
                            ->withInput()
                            ->withErrors($validator);
        }

        return Redirect::to('dbBackupDownloadLogReport?generate=true&' . $url);
    }

}
