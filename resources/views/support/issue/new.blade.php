@extends('support.layout.main')
@section('title') eClat Healthcare Incident Log - Add New Incident @endsection
@section('content')
                    <div class="container">
                        <div class="">
                            <form method="post" action="{{action('IssueController@Submit')}}" name="issue_form" id="issue_form" enctype="multipart/form-data">
                                <div class="login-form-body">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="form-gp">
                                                <h4 class="header-title mb-0">Facility</h4>
                                                <select name="facility" class="custom-select border-0 pr-3" required>
                                                    <option value="" selected="">Select One</option>
                                                    @foreach($facilities as $facility)
                                                    <option value="{{$facility->id}}">{{$facility->name}}</option>
                                                        
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <input name="_token" type="hidden" value="{{ csrf_token() }}"/>
                                        <div class="col-sm-3">
                                            <div class="form-gp">
                                                <h4 class="header-title mb-0">Type</h4>
                                                <select name = "type" id="type" class="custom-select border-0 pr-3" required>
                                                    <option value="" selected="">Select One</option>
                                                    <option value="Issue">Issue</option>
                                                    <option value="Request">Request</option>
                                                    <option value="Other">Others</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-gp">
                                                <h4  class="header-title mb-0">Incident Level</h4>
                                                <select name="level" id="level" class="custom-select border-0 pr-3" required>
                                                    <option value="" selected="">Select Type First</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-gp">
                                                <h4  class="header-title mb-0">Assign To</h4>
                                                <select name="assignedTo" id="assignedTo" class="custom-select border-0 pr-3">
                                                    <option value="">Select Level First</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <h4 class="header-title mb-0">Incident</h4>
                                    <textarea cols="73" rows="6" type="text" id="issue" name="issue" placeholder="issue" required></textarea>
                                    <script>
                                        CKEDITOR.replace( 'issue' );
                                    </script><br>
                                    <div class="row"> 
                                        <div class="col-sm-3">           
                                            <div class="form-gp">
                                                <h4 class="header-title mb-0">Incident Client Reporter</h4>
                                                <input type="text" name="clientReporter" id="clientReporter" required>
                                            </div>
                                        </div>
                                        <input type="hidden" name="url">
                                        <div class="col-sm-3">           
                                            <div class="form-gp">
                                                <h4 class="header-title mb-0">Affected Department(<span style="text-transform: lowercase;">s<span>)</h4>
                                                <input type="text" name="affectedDepartment" id="affectedDepartment" required>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">           
                                            <div class="form-gp">
                                                <h4 class="header-title mb-0">Priority</h4>
                                                <select name="priority" class="custom-select border-0 pr-3" required>
                                                    <option value="" selected="">Select One</option>
                                                    <option value="High">High</option>
                                                    <option value="Medium">Medium</option>
                                                    <option value="Low">Low</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">           
                                            <div class="form-gp">
                                                <h4 class="header-title mb-0">Incident Reported On</h4>
                                                <input type="text" id="datetimepicker" name="reportedOn" required>
                                            </div>
                                        </div>
                                        <input type="hidden" name="postType" value="0">
                                    </div>
                                </div>
                                <input type="Submit" name="submit_issue" value="Submit Incident" style="float: right;" class="btn btn-primary">
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
    <script type="text/javascript">
    $(document).ready(function(){
    $('#type').on('change',function(){
        var type = $(this).val();
        if(type){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type:'POST',
                url:'/issueType',
                data:'type='+type,
                success:function(html){
                    $('#level').html(html);
                    $('#assign').html('<option value="">Select Level first</option>'); 
                }
            }); 
        }else{
            $('#level').html('<option value="">Select Type first</option>');
            $('#assign').html('<option value="">Select Level first</option>'); 
        }
    });

    $('#level').on('change',function(){
        var level = $(this).val();
        if (level == 1) {
            var levell = 'Support Officer';
        } else if (level == 2) {
            var levell = 'Developer';
        } else if (level == 3) {
            var levell = 'Developer';
        } else if (level == 4) {
            var levell = 'Developer';
        }
        if(levell){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type:'POST',
                url:'/issueLevel',
                data:'level='+levell,
                success:function(html){
                    $('#assignedTo').html(html);
                }
            }); 
        }else{
            $('#assignedTo').html('<option value="">Select Level first</option>'); 
        }
    });
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