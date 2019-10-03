<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class IssueController extends Controller
{
    public function Create (Request $request) {
        if (!$request->session()->has('id')) {
            return view('/support/auth/login');
        }
        $state_id = $request->session()->get('state_id');
        $facilities = DB::table('facility')->where('state_id', $state_id)->get()->toArray();
        
        if ($facilities) {
            return view('/support/issue/new', [ 'facilities' => $facilities ]);
        }
    }

    public function FetchTable (Request $request) {
        $thisMonth = date('M Y');
        
        $query = DB::table('issue')
                ->join('user', 'user.id', '=', 'issue.support_officer')
                ->join('facility', 'facility.id', '=', 'facility.id');
        if (isset($thisMonth)){
            $query->where('month', $thisMonth);
        }
        $runQuery = $query->get();
        $data = array();
        foreach($runQuery as $row) {
            $subArray = array();
            $subArray[] = $row->id;
            $subArray[] = $row->code;
            $subArray[] = $row->issue_type;
            $subArray[] = $row->issue;
            $subArray[] = $row->priority;
            $subArray[] = $row->user_name;
            $subArray[] = $row->issue_date;
            $subArray[] = "<div class='dropdown'>
            <button class='btn btn-xs btn-primary dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
            Action
            </button>
            </div>";
            $subArray[] = $row->status;
            $data[] = $subArray;
        }
        $output = array(
            "draw"       =>  "1",
            "recordsTotal"   =>  "55",
            "recordsFiltered"  =>  "50",
            "data"       =>  $data
           );
        return json_encode($output);
        
        return \DataTables::of($query)->escapeColumns("actions", 
        "
        <div class='dropdown'>
            <button class='btn btn-xs btn-primary dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
            Action
            </button>
        </div>
            ")->make(true);
    }

    public function Submit (Request $request) {

        $insertIssue = DB::table('issue')->insertGetId([
            'facility'              => $request->facility,
            'issue_type'            => $request->type,
            'issue_level'           => $request->level,
            'assigned_to'           => $request->assignedTo,
            'issue'                 => $request->issue,
            'issue_client_reporter' => $request->clientReporter,
            'affected_department'   => $request->affectedDepartment,
            'priority'              => $request->priority,
            'issue_reported_on'     => date('d-m-Y @ H:i:s', strtotime($request->reportedOn)),
            'status'                => 8,
            'filter_issue_date'     => date('Y-m-d'),
            'month'                 => date('M Y'),
            'issue_date'            => date('d-m-Y H:i:s'),
            'type'                  => 0,
            'state_id'              => $request->session()->get('state_id'),
            'support_officer'       => $request->session()->get('id')
        ]);
        if ($insertIssue) {
            $insertMovement = DB::table('movement')->insert([
                'issue_id'  => $insertIssue,
                'done_by'   => $request->session()->get('id'),
                'done_at'   => date('d-m-Y H:i:s'),
                'movement'  => 'Incident was submitted'
            ]);

            if ($insertMovement) {
                $email = DB::table('user')->where('id', $request->assignedTo)->get()->toArray();
                $facility = DB::table('facility')->where('id', $request->facility)->get()->toArray();

                $subject = 'An Incident has been assigned to you';
                $message = 'Hello '.$email[0]->user_name.'<br>,
                            Incident Log S/N '.$insertIssue.' has been assigned to you by '.$request->session()->get('id').'<br>
                            <blockquote>
                                <b>Facility:</b> '.$facility[0]->name.' <br>
                                <b>Details:</b> '.$request->issue.' <br>
                            Please <a href="incident-log.eclathealthcare.com">Log in</a> and Check';
                $response = 'Incident Submitted Successfully and Mail was sent';
                $badResponse = 'Incident Submitted Successfully';
                $url = '/';
                $this->sendMail($email[0]->email, $email[0]->user_name, $subject, $message, $response, $badResponse, $url);
            }
        }
    }

    public function issueType(Request $request) {
        $query = DB::table('issue_level')->where('type', $request->type)->get()->toArray();
        if ($query) {
            foreach ($query as $row) {
                echo '<option value="'.$row->db_id.'">'.$row->level.'</option>';
            }
        }
    }

    public function issueLevel(Request $request) {
        $query = DB::table('user')->where('user_role', $request->level)->where('status', 1)->get()->toArray();
        if ($query) {
            echo '<option value="">Assign</option>';
            foreach ($query as $row) {
                echo '<option value="'.$row->id.'">'.$row->user_name.'</option>';
            }
        }
    }

    public function sendMail($emailTo, $nameTo, $subject, $message, $response, $badResponse, $url) {
        $mail = new PHPMailer(true);
        try{
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->isHTML(true);
            $mail->SMTPAuth =true;
            $mail->SMTPSecure = 'tls';
            $mail->Host = 'smtp.gmail.com'; //gmail has host > smtp.gmail.com
            $mail->Port = 587; //gmail has port > 587 . without double quotes
            $mail->Username = 'incidentlog00@gmail.com'; //your username. actually your email
            $mail->Password = 'wallace@femi'; // your password. your mail password

            $mail->setFrom('incidentlog00@gmail.com', 'Incident Log');
            $mail->addAddress($emailTo, $nameTo);

            $mail->Subject = $subject;
            $mail->Body = $message;
            $mail->send();
        }catch(phpmailerException $e){
            dd($e);
        }catch(Exception $e){
            dd($e);
        }
        if($mail){
            return redirect($url)->with("msg", $response);
        }else{
            return redirect($url)->with("msg", $badResponse);
        }
    }
}
