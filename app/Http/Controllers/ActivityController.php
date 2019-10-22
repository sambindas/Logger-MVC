<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class ActivityController extends Controller
{
    public function Dashboard(Request $request) {
        $thisMonth = date('F');
        $users = DB::table('user')
        ->where('status', 1)
        ->where('user_type', 0)
        ->where('state_id', $request->session()->get('state_id'))->get()->toArray();
        if ($request->session()->get('id') == 1 or $request->session()->get('id') == 6) {
            $facilities = DB::table('facility')->orderBy('name', 'asc')->get()->toArray();
        } else {
            $facilities = DB::table('facility')
            ->where('state_id', $request->session()->get('state_id'))
            ->orderBy('name', 'asc')->get()->toArray();
        }
        return view('/support/activity/home', ['users' => $users, 'facilities' => $facilities, 'month'=>$thisMonth]);
    }

    public function FetchActivity (Request $request) {
        $thisMonth = date('F');
        
        $query = DB::table('activity')
                ->join('user', 'user.id', '=', 'activity.user_id')
                ->join('facility', 'facility.code', '=', 'activity.facility');
        // conditional filter variables

        if($request->month != '') {
            $query->where('month', $request->month);
        } else {
            $query->where('month', $thisMonth);
        }
        
        if($request->week != '') $query->where('activity.week', $request->week);

        if($request->day != '') $query->where('activity.day', $request->day);

        if($request->logger != '')$query->where('activity.user_id', $request->logger);

        if($request->search_table != '')$query->where('activity', 'like', $request->search_table)
                                              ->orWhere('facility', 'like', $request->search_table)
                                              ->orWhere('activity_status', 'like', $request->search_table)
                                              ->orWhere('previous_status', 'like', $request->search_table)
                                              ->orWhere('comments', 'like', $request->search_table);
        
        $query1 = $query->get();
        if ($request->length != -1) {
            $query->skip($request->start)->take($request->length);
        }
        $runQuery = $query->orderBy('activity_id', 'desc')->get();
        $data = array();
        foreach($runQuery as $row) {
            $wik = date('W', strtotime($row->activity_date));
            $summary = "<div class='modal fade bd-example-modal-lg' id='osum".$row->activity_id."'>
                <div class='modal-dialog modal-notify modal-primary modal-lg'>
                    <div class='modal-content'>
                        <div class='modal-header'>
                            <h5 class='heading lead'>Other Summary of Week ".$row->week." by ".$row->user_name."</h5>
                            <button type='button' class='close' data-dismiss='modal'><span>&times;</span></button>
                        </div>
                        <div class='modal-body' style='text-align: left;'>
                            
                            <p><i><u>Unplanned Activities:</u></i> ".$row->unplanned."</p>
                            <p><i><u>Unresolved Incidents:</u></i> ".$row->unresolved."</p>
                            <p><i><u>Planned Activities (Coming Week):</u></i> ".$row->planned."</p>
                            <p><i><u>Issues for Management Attention:</u></i> ".$row->issues."</p>
                            
                        </div>
                        <div class='modal-footer'>
                        </div>
                    </div>
                </div>
            </div>";

            $actions = "<div class='dropdown'>
            <button class='btn btn-xs btn-primary dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
            Action
            </button>
            <div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>
                <a class='dropdown-item' href='activity/summary/add/".$row->activity_id."'>Add Other Summaries For This Week</a>
                <a class='dropdown-item' data-toggle='modal' href='#osum".$row->activity_id."'>Other Summaries</a>
                <a class='dropdown-item' href='activity/view/".$row->week."/".$row->month."/".$row->year."'>View Week Activity For All Users</a>
            </div>
            </div>".$summary."
            ";

            $subArray = array();
            $subArray[] = $row->activity_id;
            $subArray[] = $row->user_name;
            $subArray[] = $row->month;
            $subArray[] = $row->week;
            $subArray[] = $row->day;
            $subArray[] = $actions;
            $data[] = $subArray;
        }
        $output = array(
            "draw"       =>  $request->draw,
            "recordsTotal"   =>  $query->count(),
            "recordsFiltered"  =>  $query1->count(),
            "data"       =>  $data
           );
        echo json_encode($output);
    }
    
    public function new (Request $request) {
        $state_id = $request->session()->get('state_id');
        $facilities = DB::table('facility')->where('state_id', $state_id)->get()->toArray();
        return view ('support.activity.new', ['facilities'=>$facilities]);
    }

    public function edit (Request $request) {
        $id = $request->route('id');
        $state_id = $request->session()->get('state_id');
        $activities = DB::table('activity')->where('activity_id', $id)->get()->toArray();
        $facilities = DB::table('facility')->where('state_id', $state_id)->get()->toArray();
        return view ('support.activity.edit', ['facilities'=>$facilities, 'activities'=>$activities]);
    }

    public function delete (Request $request) {
        $id = $request->route('id');
        $activities = DB::table('activity')->where('activity_id', $id)->delete();
        return back()->with('msg', 'Activity Deleted');
    }

    public function Submit (Request $request) {
        if (isset($request->activity_id)) {
            $getID = DB::table('activity')->get();
            $updateActivity = DB::table('activity')->where('activity_id', $request->activity_id)->update([
                'facility'            => $request->facility,
                'activity'            => $request->activity,
                'activity_date'       => $request->activity_date,
                'visit_type'          => $request->visit_type,
                'activity_status'     => $request->status,
                'comments'            => $request->comments,
                'previous_status'     => $request->pstatus,
                'week'                => $this->weekOfMonth($request->activity_date),
                'day'                 => date('l', strtotime($request->activity_date)),
                'month'               => date('F', strtotime($request->activity_date)),
                'year'                => date('Y', strtotime($request->activity_date))
            ]);
            if($updateActivity) {
                return back()->with("msg", "Activity Edited Successfully");
                #return redirect ('activity/view/'.$request->week.'/'.$request->month.'/'.$request->year)->with("msg", "Activity Edited Successfully");
            } else {
                return back()->with("badmsg", "An Error Occured, Not Submitted");
                #return redirect ('activity/view/'.$request->week.'/'.$request->month.'/'.$request->year)->with("badmsg", "An Error Occured, Not Submitted");
            }
        }
        $insertActivity = DB::table('activity')->insert([
            'facility'            => $request->facility,
            'activity'            => $request->activity,
            'activity_date'       => $request->activity_date,
            'visit_type'          => $request->visit_type,
            'activity_status'     => $request->status,
            'comments'            => $request->comments,
            'previous_status'     => $request->pstatus,
            'date_submitted'      => date('d-m-Y H:i:s'),
            'week'                => $this->weekOfMonth($request->activity_date),
            'day'                 => date('l', strtotime($request->activity_date)),
            'month'               => date('F', strtotime($request->activity_date)),
            'year'                => date('Y', strtotime($request->activity_date)),
            'user_id'     => $request->session()->get('id')
        ]);
        if($insertActivity) {
            return back()->with("msg", "Activity Submitted Successfully");
        } else {
            return back()->with("badmsg", "An Error Occured, Not Submitted");
        }
    }

    public function weekOfMonth($date) {
        //Get the first day of the month.
        $firstOfMonth = strtotime(date("Y-m-01", strtotime($date)));
        //Apply above formula.
        return intval(date("W", strtotime($date))) - intval(date("W", $firstOfMonth)) + 1;
    }

    public function Summary(Request $request) {
        $id = $request->route('id');
        $activities = DB::table('activity')->where('activity_id', $id)->get()->toArray();
        if ($activities[0]->user_id != $request->session()->get('id')) {
            return redirect ('/activity')->with('badmsg', 'Cannot Edit Another User\'s Summary');
        }
        if ($activities[0]->issues != '' or $activities[0]->unplanned != '' or $activities[0]->planned != '' or $activities[0]->unresolved != '') {
            return view('support/activity/summary/edit', ['activities'=>$activities]);
        }
        return view('support/activity/summary/new', ['activities'=>$activities]);
    }

    public function SubmitSummary(Request $request) {
        $updateSummary = DB::table('activity')
        ->where('user_id', $request->user_id)
        ->where('week', $request->week)
        ->where('day', $request->day)
        ->where('month', $request->month)->update([
            'issues'       => $request->issues,
            'planned'      => $request->planned,
            'unplanned'    => $request->unplanned,
            'unresolved'   => $request->unresolved
        ]);
        if($updateSummary) {
            return redirect('/activity')->with("msg", "Summary Added/Edited Successfully");
        } else {
            return redirect('/activity')->with("msg", "An Error Occured, Not Submitted");
        }
    }

    public function ViewActivity(Request $request) {
        $days = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
        $activity_array = array();

        foreach($days as $day) {
            $activities = DB::table('activity')
                            ->where('week', $request->route('week'))
                            ->where('month', $request->route('month'))
                            ->where('year', $request->route('year'))
                            ->where('day', $day)
                            ->join('user', 'user.id', 'activity.user_id')
                            ->get();
            if ($activities->count() > 0) {
                $activity_array[] = $activities; 
            }  
        }
        $summaries = DB::table('activity')
                            ->where('week', $request->route('week'))
                            ->where('month', $request->route('month'))
                            ->where('year', $request->route('year'))
                            ->join('user', 'user.id', 'activity.user_id')
                            ->groupBy('user_id')
                            ->get();
        return view ('support/activity/view', ['activities'=>$activity_array, 'summaries'=>$summaries, 'week'=>$request->route('week')]);
    }
}
