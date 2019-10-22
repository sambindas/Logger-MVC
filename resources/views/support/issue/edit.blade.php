@extends('support.layout.main')
@section('title') eClat Healthcare Incident Log - Edit Incident @endsection
@section('content')
                    @foreach($issue as $issues)
                    <div class="container">
                        <div class="">
                            <form method="post" action="{{action('IssueController@Edit')}}" name="issue_form" id="issue_form" enctype="multipart/form-data">
                                <div class="login-form-body">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="form-gp">
                                                <h4 class="header-title mb-0">Facility</h4>
                                                <select name="facility" class="custom-select border-0 pr-3" required>
                                                    <option value="" selected="">Select One</option>
                                                    @foreach($facilities as $facility)
                                                    <option value="{{$facility->code}}" {{$facility->code == $issues->facility  ? 'selected' : ''}}>{{ $facility->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <input name="activity_id" type="hidden" value="{{$activity->activity_id}}">
                                        <input name="_token" type="hidden" value="{{ csrf_token() }}"/>
                                        <div class="col-sm-3">
                                            <div class="form-gp">
                                                <h4 class="header-title mb-0">Type</h4>
                                                <select name = "type" id="type" class="custom-select border-0 pr-3" required>
                                                    <option value="" selected="">Select One</option>
                                                    <option value="Issue" {{"Issue" == $issues->issue_type  ? 'selected' : ''}}>Issue</option>
                                                    <option value="Request" {{'Request' == $issues->issue_type  ? 'selected' : ''}}>Request</option>
                                                    <option value="Other" {{'Other' == $issues->issue_type  ? 'selected' : ''}}>Others</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-gp">
                                                <h4  class="header-title mb-0">Incident Level</h4>
                                                <select name="level" id="level" class="custom-select border-0 pr-3" required>
                                                    <option value="" selected="">Select Type First</option>
                                                    <option value="1" {{1 == $issues->issue_level  ? 'selected' : ''}}>Level One (1 hr - 24 hrs)</option>
                                                    <option value="2" {{2 == $issues->issue_level  ? 'selected' : ''}}>Level Two (24 hrs - 1 wk)</option>
                                                    <option value="3" {{3 == $issues->issue_level  ? 'selected' : ''}}>Level Three (1 wk - 1mth)</option>
                                                    <option value="4" {{4 == $issues->issue_level  ? 'selected' : ''}}>Level Four (TBD)</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <h4 class="header-title mb-0">Incident</h4>
                                    <textarea cols="73" rows="6" type="text" id="issue" name="issue" placeholder="issue" required>{{$issues->issue}}</textarea>
                                    <script>
                                        CKEDITOR.replace( 'issue' );
                                    </script><br>
                                    <div class="row"> 
                                        <div class="col-sm-3">           
                                            <div class="form-gp">
                                                <h4 class="header-title mb-0">Incident Client Reporter</h4>
                                                <input type="text" name="clientReporter" id="clientReporter" value="{{$issues->issue_client_reporter}}" required>
                                            </div>
                                        </div>
                                        <input type="hidden" name="issue_id" value="{{$issues->issue_id}}">
                                        <div class="col-sm-3">           
                                            <div class="form-gp">
                                                <h4 class="header-title mb-0">Affected Department(<span style="text-transform: lowercase;">s<span>)</h4>
                                                <input type="text" name="affectedDepartment" id="affectedDepartment" value="{{$issues->affected_department}}" required>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">           
                                            <div class="form-gp">
                                                <h4 class="header-title mb-0">Priority</h4>
                                                <select name="priority" class="custom-select border-0 pr-3" required>
                                                    <option value="" selected="">Select One</option>
                                                    <option value="High" {{'High' == $issues->priority ? 'selected' : ''}}>High</option>
                                                    <option value="Medium" {{'Medium' == $issues->priority ? 'selected' : ''}}>Medium</option>
                                                    <option value="Low" {{'Low' == $issues->priority ? 'selected' : ''}}>Low</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">           
                                            <div class="form-gp">
                                                <h4 class="header-title mb-0">Incident Reported On</h4>
                                                <input type="text" id="datetimepicker" name="reportedOn" value="{{$issues->issue_reported_on}}" required>
                                            </div>
                                        </div>
                                        <input type="hidden" name="postType" value="0">
                                    </div>
                                </div>
                                <input type="Submit" name="submit_issue" value="Edit Incident" style="float: right;" class="btn btn-primary">
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        <!-- main content area end -->
        <!-- footer area start-->
        <footer>
            <div class="footer-area">
                <p>© Copyright 2019. All right reserved.</p>
            </div>
        </footer>
        <!-- footer area end-->
    </div>
    @endsection
    @section('js')
    
    <script>
        $(document).ready(function(){
            
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function(){
            jQuery('#datetimepicker').datetimepicker();
            $('#filters').click(function(){
                    $('#filterid').toggle();
            });
            jQuery('#datetimepicker1').datetimepicker({
             i18n:{
              de:{
               months:[
                'January','February','March','April',
                'May','June','July','August',
                'September','October','November','December',
               ],
               dayOfWeek:[
                "Su.", "Mo", "Tu", "We", 
                "Th", "Fr", "Sa.",
               ]
              }
             },
             timepicker:false,
             format:'d/m/Y'
            });
        });

         $('#submit_issue').click(function(){
            var issue = $('#issue').val();

            if (issue == '') {
                alert('Please Fill the issue');
                return false;
            }
         });
    </script>
    <script>
        $(document).ready(function(){
            $('#assign').on('change',function(){
                var drop = $('#smail');
                if (drop.val() == '') {
                    drop.hide();
                } else {
                    drop.show();
                }
            });
        });
    </script>
@endsection
</html>