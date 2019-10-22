@extends('support.layout.main')
@section('title') eClat Healthcare Incident Log - Activity Dashboard @endsection
@section('content')
            <!-- page title area start -->
            <div class="page-title-area">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <div class="breadcrumbs-area clearfix">
                            <h4 class="page-title pull-left">Dashboard</h4><br><br>
                            <ul class="breadcrumbs pull-left">
                                <li><a href="index.php">Home</a></li>
                                <li><span>Incident Log</span></li>
                                <li><span></span></li>
                                <li><span></span></li>
                                <a href="{{url('/activity/new')}}" id="newissue" class="btn btn-primary btn-flat">New Activity</a>
                                <li>
                                @if(Session::get('msg'))
                                <span class="alert alert-success">{{ Session::get('msg')}}</span>
                                @endif
                                @if(Session::get('badmsg'))
                                <span class="alert alert-danger">{{ Session::get('badmsg')}}</span>
                                @endif
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-6 clearfix">
                        <div class="user-profile pull-right">
                            <img class="avatar user-thumb" src="assets/images/author/avatar.png" alt="avatar">
                            <h4 class="user-name dropdown-toggle" data-toggle="dropdown">{{ Session::get('name')}}<i class="fa fa-angle-down"></i></h4>
                            <div class="dropdown-menu">
                                <a data-toggle='modal' data-target="#switch" class="dropdown-item">Switch States</a>
                                <a class="dropdown-item" href="settings.php">Settings</a>
                                <a class="dropdown-item" href="changepassword.php">Change Password</a>
                                <a class="dropdown-item" href="help.php">Help</a>
                                <a class="dropdown-item" href="{{url('/logout')}}">Log Out</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class='modal fade' id='switch'>
                <div class='modal-dialog modal-notify modal-primary modal-notify modal-primary modal-sm'>
                    <div class='modal-content'>
                        <div class='modal-header'>
                            <h5 class='heading lead'>Switch States</h5>
                            <button type='button' class='close' data-dismiss='modal'><span>&times;</span></button>
                        </div>
                        <div class='modal-body'>
                            <form method="post" action="processing.php">
                                <select name="state" class="custom-select border-0 pr-3" required>
                                    <option value="" selected="">Select State</option>
                                </select>
                                <br><button type='submit' class='btn btn-primary' name='submit_switch'>Switch</button>
                            </form><br>
                        </div>
                        <div class='modal-footer'>
                        </div>
                    </div>
                </div>
            </div>
            <!-- page title area end -->
            <div class="main-content-inner">
                <div class="row">
                    
                    <!-- Primary table start -->
                    <div class="col-12 mt-5">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="header-title">Activity Log for echo $noww;</h4>
                                <hr>
                                <form action="javascript:;" id="filterid" method="post">

                                <div class="form-group row">
                                    <div class="col-xs-2">
                                        <label>Month</label>
                                        <select class="form-control" id="month">
                                            <option value="">Select One</option>
                                            <option value="January">January</option>
                                            <option value="February">February</option>
                                            <option value="March">March</option>
                                            <option value="April">April</option>
                                            <option value="May">May</option>
                                            <option value="June">June</option>
                                            <option value="July">July</option>
                                            <option value="August">August</option>
                                            <option value="September">September</option>
                                            <option value="October">October</option>
                                            <option value="November">November</option>
                                            <option value="December">December</option>
                                        </select>
                                    </div>&nbsp;&nbsp; &nbsp;&nbsp;
                                    <div class="col-xs-2">
                                        <label>Week</label>
                                        <select class="form-control" id="week">
                                            <option value="">Select One</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                        </select>
                                    </div>&nbsp;&nbsp; &nbsp;&nbsp;
                                    <div class="col-xs-2">
                                        <label>Day</label>
                                        <select class="form-control" id="day">
                                            <option value="">Select One</option>
                                            <option value="Monday">Monday</option>
                                            <option value="Tuesday">Tuesday</option>
                                            <option value="Wednesday">Wednesday</option>
                                            <option value="Thursday">Thursday</option>
                                            <option value="Friday">Friday</option>
                                            <option value="Saturday">Saturday</option>
                                            <option value="Sunday">Sunday</option>
                                        </select>
                                    </div>&nbsp;&nbsp; &nbsp;&nbsp;
                                    <div class="col-sm-2">
                                        <label>Activity Logger</label>
                                        <select class="form-control" id="logger">
                                            <option value="">Select One</option>
                                            @foreach($users as $user)
                                            <option value="{{$user->id}}">{{$user->user_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>&nbsp;&nbsp;
                                    <div class="col-sm-2">
                                    <label>&nbsp;&nbsp;</label><br>
                                        <input type="submit" name="filter" id="filter" class="btn-flat btn btn-primary btn-xs" value="Filter">
                                    </div>
                                </div>
                                <hr>
                                
                                </form><br>
                                <div class="data-tables datatable-primary">

                                    <div class="col-xs-12" style="float: right">
                                        <form>
                                            <input type="text" name="search_table" id="search_table" class="form-control" placeholder="Search">
                                        </form>
                                    </div>
                                    <div id="my_table">
                                        <table id="dataTable2" class="text-center cell-border">
                                            <thead class="text-capitalize">
                                                <tr>
                                                    <th>S/N</th>
                                                    <th>Support Officer</th>
                                                    <th>Month</th>
                                                    <th>Week</th>
                                                    <th>Day</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Primary table end -->
                    <div class='modal fade bd-example-modal-sm' id='launch'>
                        <div class='modal-dialog modal-notify modal-success modal-lg'>
                            <div class='modal-content'>
                                <div class='modal-header'>
                                    <h5 class='heading lead'>Successful</h5>
                                    <button type='button' class='close' data-dismiss='modal'><span>&times;</span></button>
                                </div>
                                <div class='modal-body' id='success' style='text-align: left;'>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- main content area end -->
        <!-- footer area start-->
        <footer>
            <div class="footer-area">
                <p>Copyright 2019. All right reserved.</p>
            </div>
        </footer>
        <!-- footer area end-->
    </div>
    @endsection
    @section('js')
    
    <script type="text/javascript">
    $(document).ready(function(){
            jQuery('#datetimepicker2').datetimepicker({
                format: 'Y-m-d',
                timepicker:false,
                maxDate: '0d',
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
             format:'Y-m-d',
             timepicker:false,
             maxDate: '0d',
            });
        });
    </script>

    <!-- Start datatable js -->
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
    <script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>
    <script>
    function doAction(issueID, actionType, modalName, responseOne, responseTwo, responseZero){
        $('#'+actionType+issueID).submit(function(){
            $('.submit-btn').prop('disabled', true);
            $('.modalText').html('Processing...');
            let dataset = $(this).serialize();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '/processAction',
                type: 'post',
                data: {dataset, actionType: actionType},
                success: function(response){
                    if (response == 1) {
                        console.log(response)
                        $('#'+modalName+issueID).modal('hide');
                        $('#success').html(responseOne);
                        $('#launch').modal('show');
                    } else if (response == 2) {
                        console.log(response)
                        $('#'+modalName+issueID).modal('hide');
                        $('#success').html(responseTwo);
                        $('#launch').modal('show');
                    } else if (response == 0) {
                        console.log(response)
                        $('#'+modalName+issueID).modal('hide');
                        $('#success').html(responseZero);
                        $('#launch').modal('show');
                    }
                }
            });

        });
        }
    </script>
    <script type="text/javascript" language="javascript" >
     $(document).ready(function(){
      $.fn.dataTable.ext.errMode = 'none';
        fill_datatable();
      
        
      function fill_datatable(month='', week='', day='', logger='', search_table='')
      {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
       var dataTable = $('#dataTable2').DataTable({
        
        "processing" : true,
        "pageLength": 25,
        "columnDefs": [
            { "searchable": true, "targets": 0 }
          ],
        "serverSide" : true,
        "createdRow": function(row, data, index) {

            switch (data[8]) {
                case 0: 
                    $(row).css('background-color', 'white');
                    break;
                case 1:
                    $(row).css('background-color', '#f49b42');
                    break;
                case 2: 
                    $(row).css('background-color', '#7d998b');
                    break;
                case 3:
                    $(row).css('background-color', '#42f45f');
                    break;
                case 4: 
                    $(row).css('background-color', '#f4e624');
                    break;
                case 5:
                    $(row).css('background-color', '#5394ed');
                    break;
                case 6: 
                    $(row).css('background-color', '#42ebf4');
                    break;
                case 7:
                    $(row).css('background-color', '#f95454');
                    break;
                case 8: 
                    $(row).css('background-color', '#f6f6ad');
                    break;
                case 9: 
                    $(row).css('background-color', '#e777e3');
                    break;
                default:
                    $(row).css('background-color', 'white');
            }
        },
        "order" : [],
        "searching" : false,
        "ajax" : {
         url:"{{url('/fetchActivity')}}",
         type:"post",
         data:{
          month:month, week:week, search_table:search_table, logger:logger, day:day
         }
        },
       });
      }
      $(document).on( 'keyup', '#search_table', function () {
        var search_table = $('#search_table').val();
        var logger = $('#logger').val();
        var month = $('#month').val();
        var week = $('#week').val();
        var day = $('#day').val();
        
        if(search_table != '')
           {
            $('#dataTable2').DataTable().destroy();
            fill_datatable(month, week, day, logger, search_table);
           }
           else
           {
            $('#dataTable2').DataTable().destroy();
            fill_datatable(); }
        } );

      $(document).on( 'change', '#month', function () {
        var logger = $('#logger').val();
        var month = $('#month').val();
        var week = $('#week').val();
        var day = $('#day').val();
        
        if(month != '')
           {
            $('#dataTable2').DataTable().destroy();
            fill_datatable(month, week, day, logger);
           }
           else
           {
            $('#dataTable2').DataTable().destroy();
            fill_datatable(); }
        } );

      $(document).on( 'change', '#week', function () {
        var logger = $('#logger').val();
        var month = $('#month').val();
        var week = $('#week').val();
        var day = $('#day').val();
        
        if(week != '')
           {
            $('#dataTable2').DataTable().destroy();
            fill_datatable(month, week, day, logger);
           }
           else
           {
            $('#dataTable2').DataTable().destroy();
            fill_datatable(); }
        } );

      $(document).on( 'change', '#day', function () {
        var logger = $('#logger').val();
        var month = $('#month').val();
        var week = $('#week').val();
        var day = $('#day').val();
        
        if(day != '')
           {
            $('#dataTable2').DataTable().destroy();
            fill_datatable(month, week, day, logger);
           }
           else
           {
            $('#dataTable2').DataTable().destroy();
            fill_datatable(); }
        } );

      $(document).on( 'change', '#logger', function () {
        var logger = $('#logger').val();
        var month = $('#month').val();
        var week = $('#week').val();
        var day = $('#day').val();
        
        if(logger != '')
           {
            $('#dataTable2').DataTable().destroy();
            fill_datatable(month, week, day, logger);
           }
           else
           {
            $('#dataTable2').DataTable().destroy();
            fill_datatable(); }
        } );
      
      
     });
     
    </script>
    <script type="text/javascript" src="https://cdn.rawgit.com/noelboss/featherlight/1.7.13/release/featherlight.min.js"></script>
    <script type="text/javascript" src="https://cdn.rawgit.com/noelboss/featherlight/1.7.13/release/featherlight.gallery.min.js"></script>
    
    <script>
    $.featherlightGallery.prototype.afterContent = function () {
        var caption = this.$currentTarget.find('img').attr('alt');
        this.$instance.find('.caption').remove();
        $('<div class="caption">').text(caption).appendTo(this.$instance.find('.featherlight-content'));
    }
</script>
@stop

</html>