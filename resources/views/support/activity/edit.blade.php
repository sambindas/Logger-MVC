@extends('support.layout.main')
@section('title') eClat Healthcare Incident Log - Edit Activity @endsection
@section('content')
                    @foreach($activities as $activity)
                    <div class="container">
                        @if(Session::get('msg'))
                        <span class="alert alert-success">{{ Session::get('msg')}}</span>
                        @endif
                        @if(Session::get('badmsg'))
                        <span class="alert alert-danger">{{ Session::get('badmsg')}}</span>
                        @endif
                        <div class="">
                            <form method="post" action="{{url('/activity/submit')}}" name="activity_form" id="activity_form" enctype="multipart/form-data">
                                <div class="login-form-body">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="form-gp">
                                                <h4 class="header-title mb-0">Facility</h4>
                                                <select name="facility" class="custom-select border-0 pr-3" required>
                                                    <option value="" selected="">Select One</option>
                                                    @foreach($facilities as $facility)
                                                    <option value="{{$facility->code}}"{{$facility->code == $activity->facility  ? 'selected' : ''}}>{{ $facility->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <input name="activity_id" type="hidden" value="{{ $activity->activity_id }}"/>
                                        <input name="_token" type="hidden" value="{{ csrf_token() }}"/>
                                        <div class="col-sm-3">
                                            <div class="form-gp">
                                                <h4 class="header-title mb-0">Visit Type</h4>
                                                <select name="visit_type" class="custom-select border-0 pr-3" required>
                                                    <option value="" selected="">Select One</option>
                                                    <option value="Onsite" {{"Onsite" == $activity->visit_type  ? 'selected' : ''}}>Onsite</option>
                                                    <option value="Remote" {{"Remote" == $activity->visit_type  ? 'selected' : ''}}>Remote</option>
                                                    <option value="Not Applicable"  {{"Not Applicable" == $activity->visit_type  ? 'selected' : ''}}>Not Applicable</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-gp">
                                                <h4 class="header-title mb-0">Status</h4>
                                                <select name="status" class="custom-select border-0 pr-3" required>
                                                    <option value="" selected="">Select One</option>
                                                    <option value="Complete (All Done)" {{"Complete (All Done)" == $activity->activity_status  ? 'selected' : ''}}>Complete (All Done)</option>
                                                    <option value="Incomplete (Partially Done)" {{"Incomplete (Partially Done)" == $activity->activity_status  ? 'selected' : ''}}>Incomplete (Partially Done)</option>
                                                    <option value="Pending (Escalated)" {{"Pending (Escalated)" == $activity->activity_status  ? 'selected' : ''}}>Pending (Escalated)</option>
                                                    <option value="Not Applicable" {{"Not Applicable" == $activity->activity_status  ? 'selected' : ''}}>Not Applicable</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">           
                                            <div class="form-gp">
                                                <h4 class="header-title mb-0">Activity Date</h4>
                                                <input type="text" id="datetimepicker" value="{{$activity->activity_date}}" name="activity_date" required>
                                            </div>
                                        </div>
                                    <h4 class="header-title mb-0">Activity Description</h4>
                                    <textarea cols="73" rows="6" type="text" id="issue" name="activity" placeholder="issue" required>{{$activity->activity}}</textarea>
                                    <script>
                                        CKEDITOR.replace( 'issue' );
                                    </script><br>
                                    <h4 class="header-title mb-0">Status Comments</h4>
                                    <textarea cols="73" rows="6" type="text" id="comments" name="comments" placeholder="comments" required>{{$activity->comments}}</textarea>
                                    <script>
                                        CKEDITOR.replace( 'comments' );
                                    </script><br>
                                    <h4 class="header-title mb-0">Previous Status</h4>
                                    <textarea cols="73" rows="6" type="text" id="pstatus" name="pstatus" placeholder="previous status" required>{{$activity->previous_status}}</textarea>
                                    <script>
                                        CKEDITOR.replace( 'pstatus' );
                                    </script><br>
                                </div>
                                <input type="Submit" name="submit_activity" value="Edit Activity" style="float: right;" class="btn btn-primary">
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        <!-- main content area end -->
    </div>
    @endsection
    @section('js')
    <script type="text/javascript">
        $(document).ready(function(){
            var curr = new Date;
            var firstday = new Date(curr.setDate(curr.getDate() - curr.getDay()));
            jQuery('#datetimepicker').datetimepicker({
                timepicker: false,
                maxDate: '0d',
                minDate: firstday
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
    <script type="text/javascript">
        $(document).ready(function(){
            jQuery('#datetimepicker').datetimepicker();
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
    </script>
@endsection
</html>