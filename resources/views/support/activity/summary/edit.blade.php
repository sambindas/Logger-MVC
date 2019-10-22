@extends('support.layout.main')
@section('title') eClat Healthcare Incident Log - Edit Summary @endsection
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
                            <form method="post" action="{{url('/activity/summary/submit')}}" name="activity_form" id="activity_form" enctype="multipart/form-data">
                                <div class="login-form-body">
                                    <div class="row">
                                        <!-- <h5>Edit Summary For Week </h5><br><br> -->
                                        <input name="month" type="hidden" value="{{$activity->month}}">
                                        <input name="week" type="hidden" value="{{$activity->week}}">
                                        <input name="user_id" type="hidden" value="{{$activity->user_id}}">
                                        <input name="day" type="hidden" value="{{$activity->day}}">
                                        <input name="_token" type="hidden" value="{{ csrf_token() }}"/>
                                    </div>
                                    <h4 class="header-title mb-0">Unplanned Activities</h4>
                                    <textarea cols="73" rows="6" type="text" id="unplanned" name="unplanned" placeholder="unplanned" required>{{$activity->unplanned}}</textarea>
                                    <script>
                                        CKEDITOR.replace( 'unplanned' );
                                    </script><br>
                                    <h4 class="header-title mb-0">Unresolved Incidents</h4>
                                    <textarea cols="73" rows="6" type="text" id="unresolved" name="unresolved" placeholder="unresolved" required>{{$activity->unresolved}}</textarea>
                                    <script>
                                        CKEDITOR.replace( 'unresolved' );
                                    </script><br>
                                    <h4 class="header-title mb-0">Planned Activities (Coming Week)</h4>
                                    <textarea cols="73" rows="6" type="text" id="planned" name="planned" placeholder="Planned" required>{{$activity->planned}}</textarea>
                                    <script>
                                        CKEDITOR.replace( 'planned' );
                                    </script><br>
                                    <h4 class="header-title mb-0">Issues for Management Attention</h4>
                                    <textarea cols="73" rows="6" type="text" id="issues" name="issues" placeholder="Issues" required>{{$activity->issues}}</textarea>
                                    <script>
                                        CKEDITOR.replace( 'issues' );
                                    </script><br>
                                </div>
                                <input type="Submit" name="edit_summary" value="Edit Summary" style="float: right;" class="btn btn-primary">
                            </form>
                        </div>
                    </div>
                    @endforeach
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