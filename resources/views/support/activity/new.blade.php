@extends('support.layout.main')
@section('title') eClat Healthcare Incident Log - Add New Activity @endsection
@section('content')
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
                                                    <option value="{{$facility->code}}">{{$facility->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <input name="_token" type="hidden" value="{{ csrf_token() }}"/>
                                        <div class="col-sm-3">
                                            <div class="form-gp">
                                                <h4 class="header-title mb-0">Visit Type</h4>
                                                <select name="visit_type" class="custom-select border-0 pr-3" required>
                                                    <option value="" selected="">Select One</option>
                                                    <option value="Onsite">Onsite</option>
                                                    <option value="Remote">Remote</option>
                                                    <option value="Not Applicable">Not Applicable</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-gp">
                                                <h4 class="header-title mb-0">Status</h4>
                                                <select name="status" class="custom-select border-0 pr-3" required>
                                                    <option value="" selected="">Select One</option>
                                                    <option value="Complete (All Done)">Complete (All Done)</option>
                                                    <option value="Incomplete (Partially Done)">Incomplete (Partially Done)</option>
                                                    <option value="Pending (Escalated)">Pending (Escalated)</option>
                                                    <option value="Not Applicable">Not Applicable</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">           
                                            <div class="form-gp">
                                                <h4 class="header-title mb-0">Activity Date</h4>
                                                <input type="text" id="datetimepicker" name="activity_date" required>
                                            </div>
                                        </div>
                                    <h4 class="header-title mb-0">Activity Description</h4>
                                    <textarea cols="73" rows="6" type="text" id="issue" name="activity" placeholder="issue" required></textarea>
                                    <script>
                                        CKEDITOR.replace( 'issue' );
                                    </script><br>
                                    <h4 class="header-title mb-0">Status Comments</h4>
                                    <textarea cols="73" rows="6" type="text" id="comments" name="comments" placeholder="comments" required></textarea>
                                    <script>
                                        CKEDITOR.replace( 'comments' );
                                    </script><br>
                                    <h4 class="header-title mb-0">Previous Status</h4>
                                    <textarea cols="73" rows="6" type="text" id="pstatus" name="pstatus" placeholder="previous status" required></textarea>
                                    <script>
                                        CKEDITOR.replace( 'pstatus' );
                                    </script><br>
                                </div>
                                <input type="Submit" name="submit_activity" value="Submit Activity" style="float: right;" class="btn btn-primary">
                            </form>
                        </div>
                    </div>
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
    </script>
@endsection
</html>