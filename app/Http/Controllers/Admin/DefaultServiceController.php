<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Validator;
use App\Lead;
use App\Delivery;
use App\Invoice;
use Session;
use Redirect;
use Auth;
use Common;
use Input;
use Helper;
use File;
use Response;
use DB;
use Illuminate\Http\Request;

class DefaultServiceController extends Controller {

    public function getConfirmedOrAccomplishedRedirect(Request $request){
        $countArr['ref'] = !empty($request->ref) ? ' ' . $request->ref : '';
        $countArr['confirmed'] = $request->confirmed_count ?? 0;
        $countArr['accomplished'] = $request->accomplished_count ?? 0;
        $countArr['total'] = $countArr['confirmed'] + $countArr['accomplished'];
        $countArr['total_text'] = __('label.NO');
        $countArr['total_color'] = 'warning';
        if (!empty($countArr['total']) && $countArr['total'] != 0) {
            $countArr['total_text'] = '<span class="bold">' . $countArr['total'] . '</span>';
            $countArr['total_color'] = 'info';
        }
        $countArr['total_s'] = $countArr['total'] > 1 ? 's' : '';
        
        $view = view('layouts.default.showConfirmedOrAccomplishedRedirect', compact('request', 'countArr'))->render();
        return response()->json(['html' => $view]);
    }
}
