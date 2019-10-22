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
                            <a href="{{url('/activity/new')}}" id="newissue" class="btn btn-primary btn-flat">New Activity</a><br><br>
                            <h1>Activity For Week {{$week}}</h1><br><br>

                            @foreach ($activities as $activit)
                            @foreach ($activit as $activity)
                            <!-- <h3>{{$activity->day}}</h3><br> -->
                            @endforeach
                                <table class="table text-center" border="1" style="padding: 10px; width: 100%">
                                
                                <thead>
                                    <tr>                                            
                                        <th> <b>Officer<b> </th>
                                        <th> <b>Facilty Name</b> </th>
                                        <th> <b>Activity Description</b> </th>
                                        <th> <b>Status / Visit Type</b> </th>
                                        <th> <b>Status Comments</b> </th>
                                        <th> <b>Previous Status</b> </th>
                                        <th> <b>Date Logged</b> </th>
                                    </tr>
                                </thead> 
                                    <tr>
                                    @foreach ($activit as $activity)
                                        <td align="center">
                                        @if($activity->user_id == Session::get('id'))
                                            <a href="{{url('activity/delete/'.$activity->activity_id)}}">
                                                <!-- <i class="ti-trash" data-toggle="dropdown"></i> -->
                                                X
                                            </a>
                                            <a href="{{url('/activity/edit/'.$activity->activity_id)}}">
                                                <!-- <i class="ti-pencil" data-toggle="dropdown"></i> -->
                                                E
                                            </a><br> 
                                        @endif
                                        {{$activity->user_name}}</td>
                                        <td align="center">{{$activity->day}}</td>
                                        <td align="left">{!!$activity->activity!!}</td>
                                        <td align="center">{{$activity->activity_status}} <br> <hr> {{$activity->visit_type}}</td>
                                        <td align="left">{!!$activity->comments!!}</td>
                                        <td align="left">{!!$activity->previous_status!!}</td>
                                        <td align="center">{{$activity->date_submitted}}</td>
                                    </tr>
                                    @endforeach
                                </table><br>
                            @endforeach
                            <h2>Other Summaries</h2>
                            <table class="table text-center" border="1" style="padding: 10px; width: 100%">
                            <thead>
                                <tr>                                            
                                    <th> Officer </th>
                                    <th> Unplanned Activities </th>
                                    <th> Unresolved Incidents </th>
                                    <th> Planned Activities (Coming Week) </th>
                                    <th> Issues For Management Attention </th>
                                </tr>
                            </thead>
                            @foreach($summaries as $summary)
                                <tr> 
                                    <td align="left">{{$summary->user_name}}</td>
                                    <td align="left">{!!$summary->unplanned!!}</td>
                                    <td align="left">{!!$summary->unresolved!!}</td>
                                    <td align="left">{!!$summary->planned!!}</td>
                                    <td align="left">{!!$summary->issues!!}</td>
                                </tr>
                            @endforeach
                            </table><br>
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