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
                ->join('facility', 'facility.id', '=', 'issue.facility');

        if (isset($request->datetimepicker1) && isset($request->datetimepicker2)){
            $query->whereBetween('filter_issue_date', [$request->datetimepicker1, $request->datetimepicker2]);
        } else {
            $query->where('month', $thisMonth);
        }

        // conditional filter variables

        if($request->filter_assign != '') $query->where('assigned_to', $request->filter_assign);

        if($request->filter_status != '') $query->where('issue.issue_status', $request->filter_status);

        if($request->logger != '') $query->where('support_officer', $request->logger);

        if($request->view != '')$query->whereIn('type', [$request->view, 2]);
        
        $query1 = $query->get();
        if ($request->length != -1) {
            $query->skip($request->start)->take($request->length);
        }
        $runQuery = $query->orderBy('issue_id', 'desc')->get();
        $data = array();
        foreach($runQuery as $row) {
            $reassignDropdown = '';
            $reassignQuery = DB::table('user')->where('status', 1)->where('id', '!=', $row->assigned_to)->get();
            foreach ($reassignQuery as $reassignList) {
                $reassignDropdown .= '<option value="'.$reassignList->id.'">'.$reassignList->user_name.'</option>';
            }
            $assignedToName = DB::table('user')->where('id', $row->assigned_to)->get()->toArray();
            $done = "<div class='modal fade' id='done".$row->issue_id."'>
                <div class='modal-dialog modal-notify modal-primary'>
                    <div class='modal-content'>
                        <div class='modal-header'>
                            <h5 class='heading lead'>Add Additional Comments</h5>
                            <button type='button' class='close' data-dismiss='modal'><span>&times;</span></button>
                        </div>
                        <div class='modal-body'>
                            <p>Add Additional Comments If Available</p>
                            <form method='post' id='submit_done".$row->issue_id."' action='javascript:;'>
                                <div class='md-form'>    
                                    <textarea type='text' id='form79textarea' class='md-textarea form-control' cols='40' name='comments'></textarea>
                                </div>
                                <input type='hidden' name='issue_id' value='".$row->issue_id."'><br>
                                <br><button type='submit' id='submit_btnd'
                                onClick=\"doAction(".$row->issue_id.", 'submit_done', 'done', 'Incident Marked As Done and Mail Was Sent', 'Incident Marked as Done But Mail Was Not Sent', 'An Error Occured')\" 
                                class='btn btn-primary submit-btn' name='submit_done'>Mark as Done</button>
                                <p class='modalText'></p>
                            </form><br>
                        </div>
                    </div>
                </div>
            </div>";

            $reopen = "<div class='modal fade' id='reopen".$row->issue_id."'>
                <div class='modal-dialog modal-notify modal-primary'>
                    <div class='modal-content'>
                        <div class='modal-header'>
                            <h5 class='heading lead'>Add Additional Comments</h5>
                            <button type='button' class='close' data-dismiss='modal'><span>&times;</span></button>
                        </div>
                        <div class='modal-body'>
                            <p>Add Additional Comments If Available</p>
                            <form method='post' id='submit_reopen".$row->issue_id."' action='javascript:;'>
                                <div class='md-form'>    
                                    <textarea type='text' id='form79textarea' class='md-textarea form-control' cols='40' name='comments'></textarea>
                                </div>
                                <input type='hidden' name='issue_id' value='".$row->issue_id."'><br>
                                <br><button type='submit' id='submit_btnd'
                                onClick=\"doAction(".$row->issue_id.", 'submit_reopen', 'reopen', 'Incident Reopened and Mail Was Sent', 'Incident Reopened But Mail Was Not Sent', 'An Error Occured')\" 
                                class='btn btn-primary submit-btn' name='submit_reopen'>Reopen</button>
                                <p class='modalText'></p>
                            </form><br>
                        </div>
                    </div>
                </div>
            </div>";

            $notApplicable = "<div class='modal fade' id='notApplicable".$row->issue_id."'>
                <div class='modal-dialog modal-notify modal-primary'>
                    <div class='modal-content'>
                        <div class='modal-header'>
                            <h5 class='heading lead'>Add Additional Comments</h5>
                            <button type='button' class='close' data-dismiss='modal'><span>&times;</span></button>
                        </div>
                        <div class='modal-body'>
                            <p>Add Additional Comments If Available</p>
                            <form method='post' id='submit_notApplicable".$row->issue_id."' action='javascript:;'>
                                <div class='md-form'>    
                                    <textarea type='text' id='form79textarea' class='md-textarea form-control' cols='40' name='comments'></textarea>
                                </div>
                                <input type='hidden' name='issue_id' value='".$row->issue_id."'><br>
                                <br><button type='submit' id='submit_btnd'
                                onClick=\"doAction(".$row->issue_id.", 'submit_notApplicable', 'notApplicable', 'Incident Marked as Not Applicable and Mail Was Sent', 'Incident Marked as Not Applicable But Mail Was Not Sent', 'An Error Occured')\" 
                                class='btn btn-primary submit-btn' name='submit_notApplicable'>Mark as Not Applicable</button>
                                <p class='modalText'></p>
                            </form><br>
                        </div>
                    </div>
                </div>
            </div>";
            
            $notAnIssue = "<div class='modal fade' id='notAnIssue".$row->issue_id."'>
                <div class='modal-dialog modal-notify modal-primary'>
                    <div class='modal-content'>
                        <div class='modal-header'>
                            <h5 class='heading lead'>Add Additional Comments</h5>
                            <button type='button' class='close' data-dismiss='modal'><span>&times;</span></button>
                        </div>
                        <div class='modal-body'>
                            <p>Add Additional Comments If Available</p>
                            <form method='post' id='submit_notAnIssue".$row->issue_id."' action='javascript:;'>
                                <div class='md-form'>    
                                    <textarea type='text' id='form79textarea' class='md-textarea form-control' cols='40' name='comments'></textarea>
                                </div>
                                <input type='hidden' name='issue_id' value='".$row->issue_id."'><br>
                                <br><button type='submit' id='submit_btnd'
                                onClick=\"doAction(".$row->issue_id.", 'submit_notAnIssue', 'notAnIssue', 'Incident Marked as Not an Issue and Mail Was Sent', 'Incident Marked as Not an Issue But Mail Was Not Sent', 'An Error Occured')\" 
                                class='btn btn-primary submit-btn' name='submit_notAnIssue'>Mark as Not An Issue</button>
                                <p class='modalText'></p>
                            </form><br>
                        </div>
                    </div>
                </div>
            </div>";
            
            $notClear = "<div class='modal fade' id='notClear".$row->issue_id."'>
                <div class='modal-dialog modal-notify modal-primary'>
                    <div class='modal-content'>
                        <div class='modal-header'>
                            <h5 class='heading lead'>Add Additional Comments</h5>
                            <button type='button' class='close' data-dismiss='modal'><span>&times;</span></button>
                        </div>
                        <div class='modal-body'>
                            <p>Add Additional Comments If Available</p>
                            <form method='post' id='submit_notClear".$row->issue_id."' action='javascript:;'>
                                <div class='md-form'>    
                                    <textarea type='text' id='form79textarea' class='md-textarea form-control' cols='40' name='comments'></textarea>
                                </div>
                                <input type='hidden' name='issue_id' value='".$row->issue_id."'><br>
                                <br><button type='submit' id='submit_btnd'
                                onClick=\"doAction(".$row->issue_id.", 'submit_notClear', 'notClear', 'Incident Marked as Not CLear and Mail Was Sent', 'Incident Marked as Not CLear But Mail Was Not Sent', 'An Error Occured')\" 
                                class='btn btn-primary submit-btn' name='submit_notClear'>Mark as Not Clear</button>
                                <p class='modalText'></p>
                            </form><br>
                        </div>
                    </div>
                </div>
            </div>";

            $incomplete = "<div class='modal fade' id='incomplete".$row->issue_id."'>
                <div class='modal-dialog modal-notify modal-primary'>
                    <div class='modal-content'>
                        <div class='modal-header'>
                            <h5 class='heading lead'>Add Additional Comments</h5>
                            <button type='button' class='close' data-dismiss='modal'><span>&times;</span></button>
                        </div>
                        <div class='modal-body'>
                            <p>Add Additional Comments If Available</p>
                            <form method='post' id='submit_incomplete".$row->issue_id."' action='javascript:;'>
                                <div class='md-form'>    
                                    <textarea type='text' id='form79textarea' class='md-textarea form-control' cols='40' name='comments'></textarea>
                                </div>
                                <input type='hidden' name='issue_id' value='".$row->issue_id."'><br>
                                <br><button type='submit' id='submit_btnd'
                                onClick=\"doAction(".$row->issue_id.", 'submit_incomplete', 'incomplete', 'Incident Marked as Incomplete and Mail Was Sent', 'incident Marked as Incomplete But Mail Was Not Sent', 'An Error Occured')\" 
                                class='btn btn-primary submit-btn' name='submit_incomplete'>Mark as Incomplete</button>
                                <p class='modalText'></p>
                            </form><br>
                        </div>
                    </div>
                </div>
            </div>";

            $incompleteInformationProvided = "<div class='modal fade' id='incompleteInformationProvided".$row->issue_id."'>
                <div class='modal-dialog modal-notify modal-primary'>
                    <div class='modal-content'>
                        <div class='modal-header'>
                            <h5 class='heading lead'>Add Additional Comments</h5>
                            <button type='button' class='close' data-dismiss='modal'><span>&times;</span></button>
                        </div>
                        <div class='modal-body'>
                            <p>Add Additional Comments If Available</p>
                            <form method='post' id='submit_incompleteInformationProvided".$row->issue_id."' action='javascript:;'>
                                <div class='md-form'>    
                                    <textarea type='text' id='form79textarea' class='md-textarea form-control' cols='40' name='comments'></textarea>
                                </div>
                                <input type='hidden' name='issue_id' value='".$row->issue_id."'><br>
                                <br><button type='submit' id='submit_btnd'
                                onClick=\"doAction(".$row->issue_id.", 'submit_incompleteInformationProvided', 'incompleteInformationProvided', 'Incident Marked as Incomplete Information Provided and Mail Was Sent', 'Incident Marked as Incomplete Information Provided But Mail Was Not Sent', 'An Error Occured')\" 
                                class='btn btn-primary submit-btn' name='submit_incompleteInformationProvided'>Incomplete Info Provided</button>
                                <p class='modalText'></p>
                            </form><br>
                        </div>
                    </div>
                </div>
            </div>";

            $requiresApproval = "<div class='modal fade' id='requiresApproval".$row->issue_id."'>
                <div class='modal-dialog modal-notify modal-primary'>
                    <div class='modal-content'>
                        <div class='modal-header'>
                            <h5 class='heading lead'>Add Additional Comments</h5>
                            <button type='button' class='close' data-dismiss='modal'><span>&times;</span></button>
                        </div>
                        <div class='modal-body'>
                            <p>Add Additional Comments If Available</p>
                            <form method='post' id='submit_requiresApproval".$row->issue_id."' action='javascript:;'>
                                <div class='md-form'>    
                                    <textarea type='text' id='form79textarea' class='md-textarea form-control' cols='40' name='comments'></textarea>
                                </div>
                                <input type='hidden' name='issue_id' value='".$row->issue_id."'><br>
                                <br><button type='submit' id='submit_btnd'
                                onClick=\"doAction(".$row->issue_id.", 'submit_requiresApproval', 'requiresApproval', 'Incident Marked for Approval and Mail Was Sent', 'Incident Marked for Approval But Mail Was Not Sent', 'An Error Occured')\" 
                                class='btn btn-primary submit-btn' name='submit_requresApproval'>Mark for Approval</button>
                                <p class='modalText'></p>
                            </form><br>
                        </div>
                    </div>
                </div>
            </div>";

            $reassign = "<div class='modal fade' id='reassign".$row->issue_id."'>
                <div class='modal-dialog modal-notify modal-primary modal-notify modal-primary modal-sm'>
                    <div class='modal-content'>
                        <div class='modal-header'>
                            <h5 class='heading lead'>Reassign Incident</h5>
                            <button type='button' class='close' data-dismiss='modal'><span>&times;</span></button>
                        </div>
                        <div class='modal-body'>
                            <p>Currently assigned to</p>
                            ".$assignedToName[0]->user_name."<br><br>
                            <form method='post' id='submit_reassign".$row->issue_id."' action='javascript:;'>
                                <select name='reassign' required>
                                    <option value=''>Select New User</option>
                                    ".$reassignDropdown."
                                </select><br>
                                <input type='hidden' name='issue_id' value='".$row->issue_id."'><br>
                                <br><button type='submit' id='submit_btnd'
                                onClick=\"doAction(".$row->issue_id.", 'submit_reassign', 'reassign', 'Incident Reassigned and Mail Was Sent', 'Incident Reassigned But Mail Was Not Sent', 'An Error Occured')\" 
                                class='btn btn-primary submit-btn' name='submit_reassign'>Reassign</button>
                                <p class='modalText'></p>
                            </form><br>
                        </div>
                    </div>
                </div>
            </div>";

            $approved = "<div class='modal fade' id='approved".$row->issue_id."'>
                <div class='modal-dialog modal-notify modal-primary'>
                    <div class='modal-content'>
                        <div class='modal-header'>
                            <h5 class='heading lead'>Approve</h5>
                            <button type='button' class='close' data-dismiss='modal'><span>&times;</span></button>
                        </div>
                        <div class='modal-body'>
                            <p>Add Additional Comments</p>
                            <form method='post' id='submit_approved".$row->issue_id."' action='javascript:;'>
                            <div class='md-form'>    
                                <textarea cols='40' id='form79textarea' class='md-textarea form-control' name='comments'></textarea>
                            </div>
                                <input type='hidden' name='issue_id' value='".$row->issue_id."'><br>
                                <br><button type='submit' id='submit_btnd'
                                onClick=\"doAction(".$row->issue_id.", 'submit_approved', 'approved', 'Incident Marked as Approved and Mail Was Sent', 'Incident Marked as Approved But Mail Was Not Sent', 'An Error Occured')\" 
                                class='btn btn-success submit-btn' name='submit_approved'>Approved</button>
                                <p class='modalText'></p>
                            </form><br>
                        </div>
                    </div>
                </div>
            </div>";

            $notApproved = "<div class='modal fade' id='notApproved".$row->issue_id."'>
                <div class='modal-dialog modal-notify modal-primary'>
                    <div class='modal-content'>
                        <div class='modal-header'>
                            <h5 class='heading lead'>Not Approved</h5>
                            <button type='button' class='close' data-dismiss='modal'><span>&times;</span></button>
                        </div>
                        <div class='modal-body'>
                            <p>Add Additional Comments</p>
                            <form method='post' id='submit_notApproved".$row->issue_id."' action='javascript:;'>
                            <div class='md-form'>    
                                <textarea cols='40' id='form79textarea' class='md-textarea form-control' name='comments'></textarea>
                            </div>
                                <input type='hidden' name='issue_id' value='".$row->issue_id."'><br>
                                <br><button type='submit' id='submit_btnd'
                                onClick=\"doAction(".$row->issue_id.", 'submit_notApproved', 'notApproved', 'Incident Marked as Not Approved and Mail Was Sent', 'Incident Marked as Not Approved But Mail Was Not Sent', 'An Error Occured')\" 
                                class='btn btn-danger submit-btn' name='submit_notApproved'>Not Approved</button>
                                <p class='modalText'></p>
                            </form><br>
                        </div>
                    </div>
                </div>
            </div>";

            $addComments = "<div class='modal fade' id='addComments".$row->issue_id."'>
                <div class='modal-dialog modal-notify modal-primary' role='document'>
                    <div class='modal-content'>
                        <div class='modal-header'>
                            <h5 class='heading lead'>Add Comments</h5>
                            <button type='button' class='close' data-dismiss='modal'><span>&times;</span></button>
                        </div>
                        <div class='modal-body'>
                            <p>Add Comments to this Incident</p>
                            <form id='submit_comments".$row->issue_id."' method='post' action='javascript:;'>
                            <div class='md-form'>    
                                <textarea type='text' id='form79textarea' class='md-textarea form-control' cols='40' name='comments' required></textarea>
                            </div>
                                <input type='hidden' id='".$row->support_officer."' name='support_officer' value='".$row->support_officer."'><br>
                                <input type='hidden' id='".$row->assigned_to."' name='assigned_to' value='".$row->assigned_to."'><br>
                                <input type='hidden' id='".$row->issue_id."' name='issue_id' value='".$row->issue_id."'><br>
                                <br><button type='submit' id='submit_btnd'
                                onClick=\"doAction(".$row->issue_id.", 'submit_comments', 'addComments', 'Comments Added and Mail Was Sent', 'Comments Added But Mail Was Not Sent', 'An Error Occured')\" 
                                class='btn btn-primary submit-btn' name='submit_comments'>Add Comments</button>
                                <p class='modalText'></p>
                            </form><br>
                        </div>
                    </div>
                </div>
            </div>";
            if ($row->issue_status == 0 or $row->issue_status == 8 and $row->type == 0 or $row->type == 2) {
                $actions = "<div class='dropdown'>
                            <button class='btn btn-xs btn-primary dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                            Action
                            </button>
                            <div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>
                                <a data-toggle='modal' data-target='#done".$row->issue_id."' class='dropdown-item' href='#'>Done</a>
                                <a data-toggle='modal' data-target='#notAnIssue".$row->issue_id."' class='dropdown-item' href='#'>Not an Issue</a>
                                <a data-toggle='modal' data-target='#notClear".$row->issue_id."' class='dropdown-item' href='#'>Not Clear</a>
                                <a data-toggle='modal' data-target='#requiresApproval".$row->issue_id."' class='dropdown-item' href='#'>Requires Approval</a>
                                <a data-toggle='modal' data-target='#incompleteInformationProvided".$row->issue_id."' class='dropdown-item' href='#'>Incomplete Information</a>
                                <a data-toggle='modal' data-target='#notApplicable".$row->issue_id."' class='dropdown-item' href='#'>Not Applicable</a>
                            <div class='dropdown-divider'></div>
                                <a data-toggle='modal' data-target='#addComments".$row->issue_id."' class='dropdown-item' href='#'>Add Comments</a>
                                <a data-toggle='modal' data-target='#comments".$row->issue_id."' class='dropdown-item' href='#'>View Comments</a>
                            <div class='dropdown-divider'></div>
                                <a class='dropdown-item' href=/incident/image/".$row->issue_id.">Upload Media</a>
                                <a class='dropdown-item' data-toggle='modal' href='#".$row->issue_id."media'>View Media</a>
                            <div class='dropdown-divider'></div>
                                <a class='dropdown-item' href='/incident/edit/".$row->issue_id."'>Edit Incident</a>
                                <a class='dropdown-item' data-toggle='modal' href='#reassign".$row->issue_id."'>Reassign Incident</a>
                                <a class='dropdown-item' data-toggle='modal' href='#logs".$row->issue_id."'>View Incident Movement</a>
                            </div>
                        </div>
                        ".$done."".$reopen."".$notAnIssue."".$incomplete."".$incompleteInformationProvided."".$requiresApproval."".$notApplicable."
                        ".$notClear."".$reassign."".$addComments."
                        ";
                } elseif ($row->issue_status == 0 or $row->issue_status == 8 and $row->type == 1) {
                    $actions = "<div class='dropdown'>
                            <button class='btn btn-xs btn-primary dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                            Action
                            </button>
                            <div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>
                                <a data-toggle='modal' data-target='#send".$row->issue_id."' class='dropdown-item' href='#'>Copy Incident</a>
                                <a data-toggle='modal' data-target='#done".$row->issue_id."' class='dropdown-item' href='#'>Done</a>
                                <a data-toggle='modal' data-target='#notAnIssue".$row->issue_id."' class='dropdown-item' href='#'>Not an Issue</a>
                                <a data-toggle='modal' data-target='#notClear".$row->issue_id."' class='dropdown-item' href='#'>Not Clear</a>
                                <a data-toggle='modal' data-target='#requiresApproval".$row->issue_id."' class='dropdown-item' href='#'>Requires Approval</a>
                                <a data-toggle='modal' data-target='#incompleteInformationProvided".$row->issue_id."' class='dropdown-item' href='#'>Incomplete Information</a>
                            <div class='dropdown-divider'></div>
                                <a data-toggle='modal' data-target='#addComments".$row->issue_id."' class='dropdown-item' href='#'>Add Comments</a>
                                <a data-toggle='modal' data-target='#comments".$row->issue_id."' class='dropdown-item' href='#'>View Comments</a>
                            <div class='dropdown-divider'></div>
                                <a class='dropdown-item' href=/incident/image/".$row->issue_id.">Upload Media</a>
                                <a class='dropdown-item' data-toggle='modal' href='#".$row->issue_id."media'>View Media</a>
                            <div class='dropdown-divider'></div>
                                <a class='dropdown-item' href='/incident/edit/".$row->issue_id."'>Edit Incident</a>
                                <a class='dropdown-item' data-toggle='modal' href='#reassign".$row->issue_id."'>Reassign Incident</a>
                                <a class='dropdown-item' data-toggle='modal' href='#logs".$row->issue_id."'>View Incident Movement</a>
                            </div>
                        </div>
                        ".$done."".$reopen."".$notAnIssue."".$incompleteInformationProvided."".$requiresApproval."".$notApplicable."
                        ".$notClear."".$reassign."".$addComments."
                        ";
            } elseif ($row->issue_status == 1) {
                $actions = "  <div class='dropdown'>
                                <button class='btn btn-xs btn-primary dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                Action
                                </button>
                                <div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>
                                    <a data-toggle='modal' data-target='#con".$row->issue_id."' class='dropdown-item' href='#'>Confirmed</a>
                                    <a data-toggle='modal' data-target='#incomplete".$row->issue_id."' class='dropdown-item' href='#'>Incomplete</a>
                                    <a data-toggle='modal' data-target='#reopen".$row->issue_id."' class='dropdown-item' href='#'>Reopen</a>
                                <div class='dropdown-divider'></div>
                                    <a data-toggle='modal' data-target='#addComments".$row->issue_id."' class='dropdown-item' href='#'>Add Comments</a>
                                    <a data-toggle='modal' data-target='#comments".$row->issue_id."' class='dropdown-item' href='#'>View Comments</a>
                                <div class='dropdown-divider'></div>
                                    <a class='dropdown-item' data-toggle='modal' href='#".$row->issue_id."media'>View Media</a>
                                <div class='dropdown-divider'></div>
                                    <a class='dropdown-item' href='/incident/edit/".$row->issue_id."'>Edit Incident</a>
                                    <a class='dropdown-item' data-toggle='modal' href='#logs".$row->issue_id."'>View Issue Movement</a></div>
                                </div>
                            </div>
                            ".$reopen."".$incomplete."".$reassign."".$addComments."
                        ";
            } elseif ($row->issue_status == 2) {
                $actions = '<div class="dropdown">
                            <button class="btn btn-xs btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Action
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a data-toggle="modal" data-target="#reopen'.$row->issue_id.'" class="dropdown-item" href="#">Reopen</a>
                            <div class="dropdown-divider"></div>
                                <a data-toggle="modal" data-target="#addComments'.$row->issue_id.'" class="dropdown-item" href="#">Add Comments</a>
                                <a data-toggle="modal" data-target="#comments'.$row->issue_id.'" class="dropdown-item" href="#">View Comments</a>
                            <div class="dropdown-divider"></div>
                                <a class="dropdown-item" data-toggle="modal" href="#'.$row->issue_id.'media">View Media</a>
                            <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="/incident/edit/'.$row->issue_id.'">Edit Incident</a>
                                <a class="dropdown-item" data-toggle="modal" href="#logs'.$row->issue_id.'">View Incident Movement</a></div>
                            </div>
                        </div>
                        '.$reopen.''.$reassign.''.$addComments.'
                        ';       
            } elseif ($row->issue_status == 3) {
                $actions = '  <div class="dropdown">
                            <button class="btn btn-xs btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Action
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a data-toggle="modal" data-target="#sum'.$row->issue_id.'" class="dropdown-item" href="#">View Summary</a>
                            <div class="dropdown-divider"></div>
                                <a data-toggle="modal" data-target="#addComments'.$row->issue_id.'" class="dropdown-item" href="#">Add Comments</a>
                                <a data-toggle="modal" data-target="#comments'.$row->issue_id.'" class="dropdown-item" href="#">View Comments</a>
                            <div class="dropdown-divider"></div>
                                <a class="dropdown-item" data-toggle="modal" href="#'.$row->issue_id.'media">View Media</a>
                                <a class="dropdown-item" data-toggle="modal" href="#logs'.$row->issue_id.'">View Incident Movement</a></div>
                            </div>
                        </div>
                        '.$reopen.''.$reassign.''.$addComments.'
            ';                                                            
            } elseif ($row->issue_status == 4) {
                $actions = '  <div class="dropdown">
                            <button class="btn btn-xs btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Action
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a data-toggle="modal" data-target="#done'.$row->issue_id.'" class="dropdown-item" href="#">Done</a>
                                <a data-toggle="modal" data-target="#reopen'.$row->issue_id.'" class="dropdown-item" href="#">Reopen</a>
                            <div class="dropdown-divider"></div>
                                <a data-toggle="modal" data-target="#addComments'.$row->issue_id.'" class="dropdown-item" href="#">Add Comments</a>
                                <a data-toggle="modal" data-target="#comments'.$row->issue_id.'" class="dropdown-item" href="#">View Comments</a>
                            <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="/incident/image/'.$row->issue_id.'">Upload Media</a>
                                <a class="dropdown-item" data-toggle="modal" href="#'.$row->issue_id.'media">View Media</a>
                            <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="/incident/edit/'.$row->issue_id.'">Edit Incident</a>
                                <a class="dropdown-item" data-toggle="modal" href="#reassign'.$row->issue_id.'">Reassign Incident</a>
                                <a class="dropdown-item" data-toggle="modal" href="#logs'.$row->issue_id.'">View Incident Movement</a></div>
                            </div>
                        </div>
                        '.$reopen.''.$done.''.$reassign.''.$addComments.'
                        ';
                    }
             elseif ($row->issue_status == 5) {
                $actions = '  <div class="dropdown">
                            <button class="btn btn-xs btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Action
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a data-toggle="modal" data-target="#done'.$row->issue_id.'" class="dropdown-item" href="#">Done</a>
                                <a data-toggle="modal" data-target="#reopen'.$row->issue_id.'" class="dropdown-item" href="#">Reopen</a>
                            <div class="dropdown-divider"></div>
                                <a data-toggle="modal" data-target="#addComments'.$row->issue_id.'" class="dropdown-item" href="#">Add Comments</a>
                                <a data-toggle="modal" data-target="#comments'.$row->issue_id.'" class="dropdown-item" href="#">View Comments</a>
                            <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="incident/image/'.$row->issue_id.'">Upload Media</a>
                                <a class="dropdown-item" data-toggle="modal" href="#'.$row->issue_id.'media">View Media</a>
                            <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="/incident/edit/'.$row->issue_id.'">Edit Incident</a>
                                <a class="dropdown-item" data-toggle="modal" href="#reassign'.$row->issue_id.'">Reassign Incident</a>
                                <a class="dropdown-item" data-toggle="modal" href="#logs'.$row->issue_id.'">View Incident Movement</a></div>
                        </div>
                        '.$reopen.''.$done.''.$reassign.''.$addComments.'
                        ';
                    }
             elseif ($row->issue_status == 6) {
                $actions = '  <div class="dropdown">
                            <button class="btn btn-xs btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Action
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a data-toggle="modal" data-target="#approved'.$row->issue_id.'" class="dropdown-item" href="#">Approved</a>
                                <a data-toggle="modal" data-target="#notApproved'.$row->issue_id.'" class="dropdown-item" href="#">Not Approved</a>
                            <div class="dropdown-divider"></div>
                                <a data-toggle="modal" data-target="#addComments'.$row->issue_id.'" class="dropdown-item" href="#">Add Comments</a>
                                <a data-toggle="modal" data-target="#comments'.$row->issue_id.'" class="dropdown-item" href="#">View Comments</a>
                            <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="incident/image/'.$row->issue_id.'">Upload Media</a>
                                <a class="dropdown-item" data-toggle="modal" href="#'.$row->issue_id.'media">View Media</a>
                            <div class="dropdown-divider"></div>
                                <a class="dropdown-item" data-toggle="modal" href="#logs'.$row->issue_id.'">View Incident Movement</a></div>
                            </div>
                        </div>
                        '.$approved.''.$notApproved.''.$addComments.'
                        ';
                    }
             elseif ($row->issue_status == 7) {
                $actions = '  <div class="dropdown">
                            <button class="btn btn-xs btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Action
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <div class="dropdown-divider"></div>
                                <a data-toggle="modal" data-target="#addComments'.$row->issue_id.'" class="dropdown-item" href="#">Add Comments</a>
                                <a data-toggle="modal" data-target="#comments'.$row->issue_id.'" class="dropdown-item" href="#">View Comments</a>
                            <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="incident/image/'.$row->issue_id.'">Upload Media</a>
                                <a class="dropdown-item" data-toggle="modal" href="#'.$row->issue_id.'media">View Media</a>
                            <div class="dropdown-divider"></div>
                                <a class="dropdown-item" data-toggle="modal" href="#logs'.$row->issue_id.'">View Incident Movement</a></div>
                            </div>
                        </div>
                        '.$addComments.'
                        ';
            } elseif ($row->issue_status == 9) {
                        $actions = "  <div class='dropdown'>
                            <button class='btn btn-xs btn-primary dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                            Action
                            </button>
                            <div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>
                                <a data-toggle='modal' data-target='#done".$row->issue_id."' class='dropdown-item' href='#'>Done</a>
                                <a data-toggle='modal' data-target='#notAnIssue".$row->issue_id."' class='dropdown-item' href='#'>Not an Issue</a>
                                <a data-toggle='modal' data-target='#notClear".$row->issue_id."' class='dropdown-item' href='#'>Not Clear</a>
                                <a data-toggle='modal' data-target='#requiresApproval".$row->issue_id."' class='dropdown-item' href='#'>Requires Approval</a>
                            <div class='dropdown-divider'></div>
                                <a data-toggle='modal' data-target='#addComments".$row->issue_id."' class='dropdown-item' href='#'>Add Comments</a>
                                <a data-toggle='modal' data-target='#comments".$row->issue_id."' class='dropdown-item' href='#'>View Comments</a>
                            <div class='dropdown-divider'></div>
                                <a class='dropdown-item' href='incident/image/".$row->issue_id."'>Upload Media</a>
                                <a class='dropdown-item' data-toggle='modal' href='#".$row->issue_id."media'>View Media</a>
                            <div class='dropdown-divider'></div>
                                <a class='dropdown-item' href='/incident/edit/".$row->issue_id."'>Edit Incident</a>
                                <a class='dropdown-item' data-toggle='modal' href='#reassign".$row->issue_id."'>Reassign Incident</a>
                                <a class='dropdown-item' data-toggle='modal' href='#logs".$row->issue_id."'>View Incident Movement</a>
                            </div>
                        </div>
                        ".$notAnIssue."".$addComments."".$done."".$notClear."".$requiresApproval."".$reassign."
                        ";
            } elseif ($row->issue_status == 10) {
                        $actions = "  <div class='dropdown'>
                            <button class='btn btn-xs btn-primary dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                            Action
                            </button>
                            <div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>
                            <a data-toggle='modal' data-target='#reopen".$row->issue_id."' class='dropdown-item' href='#'>Reopen</a>                
                            <div class='dropdown-divider'></div>
                                <a data-toggle='modal' data-target='#addComments".$row->issue_id."' class='dropdown-item' href='#'>Add Comments</a>
                                <a data-toggle='modal' data-target='#comments".$row->issue_id."' class='dropdown-item' href='#'>View Comments</a>
                            <div class='dropdown-divider'></div>
                                <a class='dropdown-item' href='incident/image/".$row->issue_id."'>Upload Media</a>
                                <a class='dropdown-item' data-toggle='modal' href='#".$row->issue_id."media'>View Media</a>
                            <div class='dropdown-divider'></div>
                                <a class='dropdown-item' href='/incident/edit/".$row->issue_id."'>Edit Incident</a>
                                <a class='dropdown-item' data-toggle='modal' href='#reassign".$row->issue_id."'>Reassign Incident</a>
                                <a class='dropdown-item' data-toggle='modal' href='#logs".$row->issue_id."'>View Incident Movement</a>
                            </div>
                        </div>
                        ".$reopen."".$reassign."".$addComments."
                        ";
                }
            $subArray = array();
            $subArray[] = $row->issue_id;
            $subArray[] = '<div title="'.$row->name.'">'.$row->code.'</div>';
            $subArray[] = $row->issue_type;
            $subArray[] = '<div title="Asigned To: '.$assignedToName[0]->user_name.'">'.$row->issue.'</div>';
            $subArray[] = $row->priority;
            $subArray[] = $row->user_name;
            $subArray[] = $row->issue_date;
            $subArray[] = $actions;
            $subArray[] = $row->issue_status;
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
            'issue_status'                => 8,
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
                $message = 'Hello '.$email[0]->user_name.',<br>
                            Incident Log S/N '.$insertIssue.' has been assigned to you by '.$request->session()->get('name').'<br>
                            <blockquote>
                                <b>Facility:</b> '.$facility[0]->name.' <br>
                                <b>Details:</b> '.$request->issue.' <br>
                            Please <a href="incident-log.eclathealthcare.com">Log in</a> and Check';
                $response = 'Incident Submitted Successfully and Mail was sent';
                $badResponse = 'Incident Submitted Successfully but mail was not sent';
                $result = $this->sendMail($email[0]->email, $email[0]->user_name, $subject, $message);
                
                if($result == 'sent') {
                    return redirect('/')->with("msg", $response);
                } else {
                    return redirect('/')->with("msg", $badResponse);
                }
            }else {
            }
        }
        return redirect('/')->with("badmsg", "An Error Occured, Please Try Again");
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

    public function ProcessAction(Request $request) {
        parse_str($request->dataset, $newRequest);
        $date = date('d-m-Y H:i:s');
        $issueID = $newRequest['issue_id'];
        $loggedID = $request->session()->get('id');
        $loggedName = $request->session()->get('name');

        switch ($request->actionType) {
            case 'submit_done':
            $movement = 'Incident was submitted';
            $sub = 'Incident Marked as Done';
            $mess = 'which you submitted, has been marked as DONE';
            $mailTo = 'issue.support_officer';
                $result = $this->ProcessActionFunction($issueID, 1, $loggedID, $loggedName, $newRequest['comments'], $movement, $sub, $mess, $mailTo);
                if ($result == 'sent') {
                    echo 1;
                    exit();
                } elseif ($result == 'not sent') {
                    echo 2;
                    exit();
                } else {
                    echo 0;
                }
                break;

            case 'submit_reopen':
            $movement = 'Incident was Reopened';
            $sub = 'Incident Marked as Reopened';
            $mess = 'has been REOPENED';
            $mailTo = 'issue.assigned_to';
                $result = $this->ProcessActionFunction($issueID, 8, $loggedID, $loggedName, $newRequest['comments'], $movement, $sub, $mess, $mailTo);
                if ($result == 'sent') {
                    echo 1;
                    exit();
                } elseif ($result == 'not sent') {
                    echo 2;
                    exit();
                } else {
                    echo 0;
                }
                break;

            case 'submit_notAnIssue':
            $movement = 'Incident was Marked as Not An Issue';
            $sub = 'Incident Marked as Not An Issue';
            $mess = 'which you submitted, has been marked as NOT AN ISSUE';
            $mailTo = 'issue.support_officer';
                $result = $this->ProcessActionFunction($issueID, 2, $loggedID, $loggedName, $newRequest['comments'], $movement, $sub, $mess, $mailTo);
                if ($result == 'sent') {
                    echo 1;
                    exit();
                } elseif ($result == 'not sent') {
                    echo 2;
                    exit();
                } else {
                    echo 0;
                }
                break;

            case 'submit_notClear':
            $movement = 'Incident was Marked as Not Clear';
            $sub = 'Incident Marked as Not Clear';
            $mess = 'which you submitted, has been marked as NOT CLEAR';
            $mailTo = 'issue.support_officer';
                $result = $this->ProcessActionFunction($issueID, 5, $loggedID, $loggedName, $newRequest['comments'], $movement, $sub, $mess, $mailTo);
                if ($result == 'sent') {
                    echo 1;
                    exit();
                } elseif ($result == 'not sent') {
                    echo 2;
                    exit();
                } else {
                    echo 0;
                }
                break;

            case 'submit_requiresApproval':
            $movement = 'Incident was Marked for Approval';
            $sub = 'Incident Requires Approval';
            $mess = 'which you submitted, has been marked as REQURES APPROVAL';
            $mailTo = 'issue.support_officer';
                $result = $this->ProcessActionFunction($issueID, 6, $loggedID, $loggedName, $newRequest['comments'], $movement, $sub, $mess, $mailTo);
                if ($result == 'sent') {
                    echo 1;
                    exit();
                } elseif ($result == 'not sent') {
                    echo 2;
                    exit();
                } else {
                    echo 0;
                }
                break;

            case 'submit_incomplete':
            $movement = 'Incident was Marked as Incomplete';
            $sub = 'Incident Marked as Incomplete';
            $mess = 'which you marked as done, has been marked INCOMPLETE';
            $mailTo = 'issue.assigned_to';
                $result = $this->ProcessActionFunction($issueID, 4, $loggedID, $loggedName, $newRequest['comments'], $movement, $sub, $mess, $mailTo);
                if ($result == 'sent') {
                    echo 1;
                    exit();
                } elseif ($result == 'not sent') {
                    echo 2;
                    exit();
                } else {
                    echo 0;
                }
                break;

            case 'submit_incompleteInformationProvided':
            $movement = 'Incident was Marked as Incomplete Information Provided';
            $sub = 'Incident Marked as Incomplete Information Provided';
            $mess = 'which you submitted, has been marked as INCOMPLETE INFORMATION PROVIDED';
            $mailTo = 'issue.support_officer';
                $result = $this->ProcessActionFunction($issueID, 9, $loggedID, $loggedName, $newRequest['comments'], $movement, $sub, $mess, $mailTo);
                if ($result == 'sent') {
                    echo 1;
                    exit();
                } elseif ($result == 'not sent') {
                    echo 2;
                    exit();
                } else {
                    echo 0;
                }
                break;
                
            case 'submit_notApplicable':
            $movement = 'Incident was Marked as Not Applicable';
            $sub = 'Incident Marked as Not Applicable';
            $mess = 'which you submitted, has been marked as NOT APPLICABLE';
            $mailTo = 'issue.support_officer';
                $result = $this->ProcessActionFunction($issueID, 10, $loggedID, $loggedName, $newRequest['comments'], $movement, $sub, $mess, $mailTo);
                if ($result == 'sent') {
                    echo 1;
                    exit();
                } elseif ($result == 'not sent') {
                    echo 2;
                    exit();
                } else {
                    echo 0;
                }
                break;

            case 'submit_approved':
            $movement = 'Incident was Marked as Approved';
            $sub = 'Incident Marked as Approved';
            $mess = 'which required Approval, has been marked as APPROVED';
            $mailTo = 'issue.assigned_to';
                $result = $this->ProcessActionFunction($issueID, 8, $loggedID, $loggedName, $newRequest['comments'], $movement, $sub, $mess, $mailTo);
                if ($result == 'sent') {
                    echo 1;
                    exit();
                } elseif ($result == 'not sent') {
                    echo 2;
                    exit();
                } else {
                    echo 0;
                }
                break;

            case 'submit_notApproved':
            $movement = 'Incident was Marked as Not Approved';
            $sub = 'Incident Marked as Not Approved';
            $mess = 'which required Approval, has been marked as NOT APPROVED';
            $mailTo = 'issue.assigned_to';
                $result = $this->ProcessActionFunction($issueID, 8, $loggedID, $loggedName, $newRequest['comments'], $movement, $sub, $mess, $mailTo);
                if ($result == 'sent') {
                    echo 1;
                    exit();
                } elseif ($result == 'not sent') {
                    echo 2;
                    exit();
                } else {
                    echo 0;
                }
                break;

            case 'submit_comments':
            $movement = 'Comments Were Added';
            $subject = 'New Comments Available';
            $insertComments = DB::table('comments')->insert([
                        'issue_id'  => $issueID,
                        'user_id'   => $loggedID,
                        'date_added'   => $date,
                        'comment'  => $newRequest['comments'],
                        'status'    => 20
            ]);
            
            if(!$insertComments) {
                echo 0;
                exit();
            }
            $insertMovement = DB::table('movement')->insert([
                        'issue_id'  => $issueID,
                        'done_by'   => $loggedID,
                        'done_at'   => $date,
                        'movement'  => $movement
            ]);
            $logger = DB::table('user')
                    ->where('id', $newRequest['support_officer'])
                    ->get()->toArray();
            $userAssigned = DB::table('user')
                    ->where('id', $newRequest['assigned_to'])
                    ->get()->toArray();
            # check if commenter is same user assigned the incident
            if ($userAssigned[0]->id = $request->session()->get('id')) {
                $email = array($logger[0]->email => $logger[0]->user_name);
                $loggerName = array($logger[0]->user_name);
            } else {
                $email = array($logger[0]->email => $logger[0]->user_name, $userAssigned[0]->email => $userAssigned[0]->user_name);
                $loggerName = array($logger[0]->user_name, $userAssigned[0]->email);
            }

            $message = 'Hello,<br>
            A Comment has just been added to Incident Log S/N '.$issueID.' by '.$loggedName.'<br>
            <blockquote>
                '.$newRequest['comments'].'
            </blockquote><br>
            Please <a href="incident-log.eclathealthcare.com">Log in</a> and Check';
            $result = $this->sendMail($email, $loggerName, $subject, $message);
                if ($result == 'sent') {
                    echo 1;
                    exit();
                } elseif ($result == 'not sent') {
                    echo 2;
                    exit();
                }
                break;

            case 'submit_reassign':
            $movement = 'Incident was Reassigned';
            $subject = 'An Incident Has Been Reassigned To You';
            $done = DB::table('issue')
                        ->where('issue_id', $issueID)
                        ->update([
                            'assigned_to' => $newRequest['reassign'],
                        ]);
            
            if(!$done) {
                echo 0;
                exit();
            }
            $insertMovement = DB::table('movement')->insert([
                        'issue_id'  => $issueID,
                        'done_by'   => $loggedID,
                        'done_at'   => $date,
                        'movement'  => $movement
            ]);
            $logger = DB::table('user')
                    ->where('id', $newRequest['reassign'])
                    ->get();
            
            $message = 'Hello '.$logger[0]->user_name.',<br>
            Incident Log S/N '.$issueID.' has been reassigned to you by '.$loggedName.'<br>
            Please <a href="incident-log.eclathealthcare.com">Log in</a> and Check';
            $result = $this->sendMail($logger[0]->email, $logger[0]->user_name, $subject, $message);
                if ($result == 'sent') {
                    echo 1;
                    exit();
                } elseif ($result == 'not sent') {
                    echo 2;
                    exit();
                }
                break;
            default:
                # code...
                break;
        }
    }
    
    public function ProcessActionFunction($issueID, $status, $loggedID, $loggedName, $comments, $movement, $sub, $mess, $mailTo) {
        $date = date('d-m-Y H:i:s');
        
        $done = DB::table('issue')
                    ->where('issue_id', $issueID)
                    ->update([
                        'issue_status' => $status,
                        'resolved_by' => $loggedID,
                        'resolution_date' => $date
                    ]);
        
        if ($done) {
            if ($comments != '') {
                $insertComment = DB::table('comments')->insert([
                    'comment'  => $comments,
                    'user_id'   => $loggedID,
                    'date_added'   => date('d-m-Y H:i:s'),
                    'issue_id'  => $issueID,
                    'status' => $status
                ]);
            } else {
                $comments = 'Nil';
            }
            $insertMovement = DB::table('movement')->insert([
                'issue_id'  => $issueID,
                'done_by'   => $loggedID,
                'done_at'   => date('d-m-Y H:i:s'),
                'movement'  => $movement
            ]);
        
            $logger = DB::table('issue')
                    ->where('issue_id', $issueID)
                    ->join('user', 'user.id', '=', $mailTo)
                    ->get();
            $subject = $sub;
            $message = 'Hello '.$logger[0]->user_name.',<br>
                        Incident Log S/N '.$issueID.' '.$mess.' by '.$loggedName.'<br>
                        <blockquote>
                            <b>Comments:</b> '.$comments.' <br>
                        </blockquote><br>
                        Please <a href="incident-log.eclathealthcare.com">Log in</a> and Check';
            $mailResult = $this->sendMail($logger[0]->email, $logger[0]->user_name, $subject, $message);
            if ($mailResult == 'sent') {
                $result = 'sent';
            } else {
                $result =  'not sent';
            } return $result;
        } else {
            echo 'error occured';
        }
    }

    public function sendMail($emailTo, $nameTo, $subject, $message) {
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
            if (is_array($emailTo)){
                foreach ($emailTo as $email => $name) {
                    $mail->addAddress($email, $name);
                }
            } else {
                $mail->addAddress($emailTo, $nameTo);
            }

            $mail->Subject = $subject;
            $mail->Body = $message;
            $mail->send();
        }catch(phpmailerException $e){
            dd($e);
        }catch(Exception $e){
            dd($e);
        }
        if($mail){
            $result = 'sent';
        } else {    
            $result = 'not sent';
        }
        return $result;
    }
}
