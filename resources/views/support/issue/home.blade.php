@extends('support.layout.main')
@section('title') eClat Healthcare Incident Log - Dashboard @endsection
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
                                <a href="new" id="newissue" class="btn btn-primary btn-flat">New Incident</a>
                                <li>
                                @if(Session::get('msg'))
                                <span class="alert alert-success">{{ Session::get('msg')}}</span>
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
                                <a class="dropdown-item" href="logout.php">Log Out</a>
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
                                <h4 class="header-title">
                                </h4>
                                <hr>
                                <form action="javascript:;" id="filterid">

                                <div class="form-group row">
                                    <div class="col-sm-2">
                                        <label for="ex1">From</label>
                                        <input type="text" class="form-control" id="datetimepicker1" value="<?php echo date('Y-m-01') ?>" readonly placeholder="From" name="from"><i class="ti-calender"></i>
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="ex1">To</label>
                                        <input type="text" class="form-control" id="datetimepicker2" value="<?php echo date('Y-m-d') ?>" readonly placeholder="From" name="to"><i class="ti-calender"></i><br>
                                    </div> &nbsp;
                                    <div class="col-sm-1">
                                        <label>Assigned</label>
                                        <select class="form-control" id="filter_assign">
                                            <option value="">All</option>
                                        </select>
                                    </div> &nbsp;&nbsp; &nbsp;&nbsp;
                                    <div class="col-sm-2">
                                        <label>Incident Status</label>
                                        <select class="form-control" id="filter_status">
                                            <option value="">Select One</option>
                                            <option value="0">Open / Unassigned</option>
                                            <option value="8">Open / Assigned</option>
                                            <option value="1">Done</option>
                                            <option value="3">Confirmed</option>
                                            <option value="2">Not An Issue</option>
                                            <option value="5">Not Clear</option>
                                            <option value="6">Approval Required</option>
                                            <option value="7">Not Approved</option>
                                            <option value="4">Incomplete</option>
                                            <option value="9">Incomplete Information</option>
                                        </select>
                                    </div>&nbsp;&nbsp;
                                    <div class="col-sm-2">
                                        <label>Incident Logger</label>
                                        <select class="form-control" id="logger">
                                            <option value="">Select One</option>
                                        </select>
                                    </div>&nbsp;&nbsp;
                                    <div class="col-sm-2">
                                        <label>View</label>
                                        <select class="form-control" id="view">
                                            <option value="0">Support</option>
                                            <option value="1">Client</option>
                                            ?>
                                        </select>
                                    </div>&nbsp;&nbsp;
                                    <div class="col-sm-2">
                                    <label>&nbsp;&nbsp;</label><br>
                                        <input type="submit" name="filter" id="filter" class="btn-flat btn btn-primary btn-xs" value="Filter">
                                    </div>
                                </div>


                                <!-- <div class="form-group" style="margin-bottom:20px">
                                    <div class="col-sm-12">
                                        <div class="col-sm-6">
                                            <span class="label" style="background:#93f575 !important;color:#158703">Confirmed</span>
                                            <span class="label" style="background:#f8fab3 !important;color:#158703">Awaiting attention</span>
                                            <span class="label" style="background:#faafaf !important;color:#158703">Urgent</span>
                                            <span class="label" style="background:#B9B9F9 !important;color:#158703">Booked</span>
                                        </div>
                                    </div>
                                </div>  -->
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
                                                    <th>Facility</th>
                                                    <th>Type</th>
                                                    <th align="right">Incident</th>
                                                    <th>Priority</th>
                                                    <th>Submitted By</th>
                                                    <th>Date Logged</th>
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
                <p>Copyright <?php echo date('Y'); ?>. All right reserved.</p>
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
    function dope(iss){
        // var iss = $('.submit_comm').val();
        let fid = 'submit_comm'+iss;
        console.log(fid)
        $('#'+fid).submit(function(){
            $('#submit_btnc').prop('disabled', true);
            $('#submitbtnid').html('Processing...');
            let id = 'submit_comm';
            let dataset = $(this).serialize();
            $.ajax({
                url: 'processing.php',
                type: 'post',
                data: {dataset, submit_comm: id},
                success: function(response){
                    if (response == 1) {
                        console.log(response)
                        $('#comm'+iss).modal('hide');
                        $('#success').html('Comments Added and Mail Was Sent');
                        $('#launch').modal('show');
                    } else {
                        console.log(response)
                        $('#comm'+iss).modal('hide');
                        $('#success').html('Comments Added but mail was not sent');
                        $('#launch').modal('show');
                    }
                }
            });

        });
        }

    function dope1(iss){
    // var iss = $('.submit_comm').val();
    let fid = 'submit_done'+iss;
    console.log(fid)
    $('#'+fid).submit(function(){
        $('#submit_btnd').prop('disabled', true);
        $('#submitdone').html('Processing...');
        let id = 'submit_done';
        let dataset = $(this).serialize();
        $.ajax({
            url: 'processing.php',
            type: 'post',
            data: {dataset, submit_done: id},
            success: function(response){
                if (response == 1) {
                    console.log(response)
                    $('#done'+iss).modal('hide');
                    $('#success').html('Incident marked as done and mail was sent');
                    $('#launch').modal('show');
                } else if (response == 0) {
                    console.log(response)
                    $('#done'+iss).modal('hide');
                    $('#success').html('Incident Marked as done but mail was not sent');
                    $('#launch').modal('show');
                } else {
                    $('#done'+iss).modal('hide');
                    $('#success').html('Incident Marked As Done');
                    $('#launch').modal('show');
                }
            }
        });

    });
    }

    function dope2(iss){
    // var iss = $('.submit_comm').val();
    let fid = 'submit_reo'+iss;
    console.log(fid)
    $('#'+fid).submit(function(){
        $('#submit_reod').prop('disabled', true);
        $('#submitreo').html('Processing...');
        let id = 'submit_reo';
        let dataset = $(this).serialize();
        $.ajax({
            url: 'processing.php',
            type: 'post',
            data: {dataset, submit_reo: id},
            success: function(response){
                if (response == 1) {
                    console.log(response)
                    $('#reo'+iss).modal('hide');
                    $('#success').html('Issue Reopened and Comments were added');
                    $('#launch').modal('show');
                } else if (response == 2) {
                    console.log(response)
                    $('#reo'+iss).modal('hide');
                    $('#success').html('Issue Reopened.');
                    $('#launch').modal('show');
                }
            }
        });

    });
    }

    function dope3(iss){
    let fid = 'submit_nai'+iss;
    console.log(fid)
    $('#'+fid).submit(function(){
        $('#submit_naid').prop('disabled', true);
        $('#submitnai').html('Processing...');
        let id = 'submit_nai';
        let dataset = $(this).serialize();
        $.ajax({
            url: 'processing.php',
            type: 'post',
            data: {dataset, submit_nai: id},
            success: function(response){
                if (response == 1) {
                    console.log(response)
                    $('#nai'+iss).modal('hide');
                    $('#success').html('Incident Marked as Not An Issue and Comments were added');
                    $('#launch').modal('show');
                } else if (response == 2) {
                    console.log(response)
                    $('#nai'+iss).modal('hide');
                    $('#success').html('Incident Marked as Not An Issue.');
                    $('#launch').modal('show');
                }
            }
        });

    });
    }
    
    function dope4(iss){
    let fid = 'submit_noc'+iss;
    console.log(fid)
    $('#'+fid).submit(function(){
        $('#submit_nocd').prop('disabled', true);
        $('#submitnoc').html('Processing...');
        let id = 'submit_noc';
        let dataset = $(this).serialize();
        $.ajax({
            url: 'processing.php',
            type: 'post',
            data: {dataset, submit_noc: id},
            success: function(response){
                if (response == 1) {
                    console.log(response)
                    $('#noc'+iss).modal('hide');
                    $('#success').html('Incident Marked as Not Clear and Comments were added');
                    $('#launch').modal('show');
                } else if (response == 2) {
                    console.log(response)
                    $('#noc'+iss).modal('hide');
                    $('#success').html('Incident Marked as Not Clear.');
                    $('#launch').modal('show');
                }
            }
        });

    });
    }

    function dope5(iss){
    let fid = 'submit_req'+iss;
    console.log(fid)
    $('#'+fid).submit(function(){
        $('#submit_reqd').prop('disabled', true);
        $('#submitbtnreq').html('Processing...');
        let id = 'submit_req';
        let dataset = $(this).serialize();
        $.ajax({
            url: 'processing.php',
            type: 'post',
            data: {dataset, submit_req: id},
            success: function(response){
                if (response == 1) {
                    console.log(response)
                    $('#req'+iss).modal('hide');
                    $('#success').html('Incident Marked for Approval and Comments were added');
                    $('#launch').modal('show');
                } else if (response == 2) {
                    console.log(response)
                    $('#req'+iss).modal('hide');
                    $('#success').html('Incident Marked for Approval.');
                    $('#launch').modal('show');
                }
            }
        });

    });
    }
    
    function dope6a(iss){
    let fid = 'submit_app'+iss;
    console.log(fid)
    $('#'+fid).submit(function(){
        $('#submit_appd').prop('disabled', true);
        $('#submitbtnapp').html('Processing...');
        let id = 'submit_app';
        let dataset = $(this).serialize();
        $.ajax({
            url: 'processing.php',
            type: 'post',
            data: {dataset, submit_app: id},
            success: function(response){
                if (response == 1) {
                    console.log(response)
                    $('#app'+iss).modal('hide');
                    $('#success').html('Incident Marked as Approved and Comments were added');
                    $('#launch').modal('show');
                } else if (response == 2) {
                    console.log(response)
                    $('#app'+iss).modal('hide');
                    $('#success').html('Incident Marked as Approved.');
                    $('#launch').modal('show');
                }
            }
        });

    });
    }

    function dope6b(iss){
    let fid = 'submit_app'+iss;
    console.log(fid)
    $('#'+fid).submit(function(){
        $('#submit_nappd').prop('disabled', true);
        $('#submitbtnapp').html('Processing...');
        let id = 'submit_dapp';
        let dataset = $(this).serialize();
        $.ajax({
            url: 'processing.php',
            type: 'post',
            data: {dataset, submit_dapp: id},
            success: function(response){
                if (response == 1) {
                    console.log(response)
                    $('#app'+iss).modal('hide');
                    $('#success').html('Incident Marked as Not Approved and Comments were added');
                    $('#launch').modal('show');
                } else if (response == 2) {
                    console.log(response)
                    $('#app'+iss).modal('hide');
                    $('#success').html('Incident Marked as Not Approved.');
                    $('#launch').modal('show');
                }
            }
        });

    });
    }

    function dope7(iss){
    let fid = 'submit_re'+iss;
    console.log(fid)
    $('#'+fid).submit(function(){
        $('#submit_reb').prop('disabled', true);
        $('#submitbtnre').html('Processing...');
        let id = 'submit_re';
        let dataset = $(this).serialize();
        $.ajax({
            url: 'processing.php',
            type: 'post',
            data: {dataset, submit_re: id},
            success: function(response){
                if (response == 1) {
                    console.log(response)
                    $('#re'+iss).modal('hide');
                    $('#success').html('Incident Reassigned Successfully and Mail was sent');
                    $('#launch').modal('show');
                } else if (response == 2) {
                    console.log(response)
                    $('#re'+iss).modal('hide');
                    $('#success').html('Incident Reassigned Successfully.');
                    $('#launch').modal('show');
                }
            }
        });

    });
    }
    </script>
    <script type="text/javascript" language="javascript" >
     $(document).ready(function(){
        console.log(123);
      $.fn.dataTable.ext.errMode = 'none';
        fill_datatable();
      
    
      function fill_datatable(filter_status = '', filter_assign = '', logger = '', view = 0, datetimepicker1 = '', datetimepicker2 = '', search_table = '')
      {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
       $('#dataTable2').DataTable({
        
        "processing" : true,
        "pageLength": 25,
        "serverSide" : true,
        "createdRow": function(row, data, index) {

            switch (data[8]) {
                case '0': 
                    $(row).css('background-color', 'white');
                    break;
                case '1':
                    $(row).css('background-color', '#f49b42');
                    break;
                case '2': 
                    $(row).css('background-color', '#7d998b');
                    break;
                case '3':
                    $(row).css('background-color', '#42f45f');
                    break;
                case '4': 
                    $(row).css('background-color', '#f4e624');
                    break;
                case '5':
                    $(row).css('background-color', '#5394ed');
                    break;
                case '6': 
                    $(row).css('background-color', '#42ebf4');
                    break;
                case '7':
                    $(row).css('background-color', '#f95454');
                    break;
                case '8': 
                    $(row).css('background-color', '#f6f6ad');
                    break;
                case '9': 
                    $(row).css('background-color', '#e777e3');
                    break;
                default:
                    $(row).css('background-color', 'white');
            }
        },
        "order" : [],
        "searching" : false,
        "ajax" : {
         url:"/fetchTable",
         type:"POST",
         data:{
          filter_status:filter_status, logger:logger, view:view, filter_assign:filter_assign, datetimepicker1:datetimepicker1, datetimepicker2:datetimepicker2, search_table:search_table
         }
        },
        "columnDefs": [
            { "width": "40%", "targets": 3,
            "className": "text-justify", "targets": 3,
            "searchable": true, "targets": 3,
             }
          ]
       });
      }
      //perform this when success modal is closed
      $('#launch').on('hidden.bs.modal', function () {
            $('#dataTable2').DataTable().destroy();

            var filter_status = $('#filter_status').val();
            var filter_assign = $('#filter_assign').val();
            var search_table = $('#search_table').val();
            var datetimepicker1 = $('#datetimepicker1').val();
            var datetimepicker2 = $('#datetimepicker2').val();
            var logger = $('#logger').val();
            var view = $('#view').val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#dataTable2').DataTable({
                "processing" : true,
                "pageLength": 25,
                "columnDefs": [
                    { "searchable": true, "targets": 0 }
                ],
                "serverSide" : true,
                "createdRow": function(row, data, index) {

                    switch (data[8]) {
                        case '0': 
                            $(row).css('background-color', 'white');
                            break;
                        case '1':
                            $(row).css('background-color', '#f49b42');
                            break;
                        case '2': 
                            $(row).css('background-color', '#7d998b');
                            break;
                        case '3':
                            $(row).css('background-color', '#42f45f');
                            break;
                        case '4': 
                            $(row).css('background-color', '#f4e624');
                            break;
                        case '5':
                            $(row).css('background-color', '#5394ed');
                            break;
                        case '6': 
                            $(row).css('background-color', '#42ebf4');
                            break;
                        case '7':
                            $(row).css('background-color', '#f95454');
                            break;
                        case '8': 
                            $(row).css('background-color', '#f6f6ad');
                            break;
                        case '9': 
                            $(row).css('background-color', '#e777e3');
                            break;
                        default:
                            $(row).css('background-color', 'white');
                    }
                },
                "order" : [],
                "searching" : false,
                "ajax" : {
                url:"ajax/fetch.php",
                type:"POST",
                data:{
                filter_status:filter_status, logger:logger, view:view, filter_assign:filter_assign, datetimepicker1:datetimepicker1, datetimepicker2:datetimepicker2, search_table:search_table
                }
                },
                "columnDefs": [
                    { "width": "40%", "targets": 3,
                    "className": "text-justify", "targets": 3,
                    "searchable": true, "targets": 3,
                    }
                ]
            });
        });
      $(document).on( 'keyup', '#search_table', function () {
        var filter_status = $('#filter_status').val();
        var filter_assign = $('#filter_assign').val();
        var search_table = $('#search_table').val();
        var datetimepicker1 = $('#datetimepicker1').val();
        var datetimepicker2 = $('#datetimepicker2').val();
        var logger = $('#logger').val();
        var view = $('#view').val();
        
        if(search_table != '')
           {
            $('#dataTable2').DataTable().destroy();
            fill_datatable(filter_status, filter_assign, logger, view, datetimepicker1, datetimepicker2, search_table);
           }
           else
           {
            $('#dataTable2').DataTable().destroy();
            fill_datatable(filter_status, filter_assign); }
        } );
      $(document).on("change", "#filter_assign", function(){
       var filter_status = $('#filter_status').val();
       var filter_assign = $('#filter_assign').val();
       var logger = $('#logger').val();
       var view = $('#view').val();

       if(filter_status != '' || filter_assign != '' || logger !='' || view != '')
       {
        $('#dataTable2').DataTable().destroy();
        fill_datatable(filter_status, filter_assign, logger, view);
       }
       else
       {
        $('#dataTable2').DataTable().destroy();
        fill_datatable();
       }
      });

      $(document).on("change", "#logger", function(){
       var filter_status = $('#filter_status').val();
       var filter_assign = $('#filter_assign').val();
       var logger = $('#logger').val();
       var view = $('#view').val();

       if(filter_status != '' || filter_assign != '' || logger !='' || view !='')
       {
        $('#dataTable2').DataTable().destroy();
        fill_datatable(filter_status, filter_assign, logger, view);
       }
       else
       {
        $('#dataTable2').DataTable().destroy();
        fill_datatable();
       }
      });

      $(document).on("change", "#view", function(){
       var filter_status = $('#filter_status').val();
       var filter_assign = $('#filter_assign').val();
       var logger = $('#logger').val();
       var view = $('#view').val();

       if(filter_status != '' || filter_assign != '' || logger !='' || view != '')
       {
        $('#dataTable2').DataTable().destroy();
        fill_datatable(filter_status, filter_assign, logger, view);
       }
       else
       {
        $('#dataTable2').DataTable().destroy();
        fill_datatable();
       }
      });

      $(document).on("change", "#filter_status", function(){
       var filter_status = $('#filter_status').val();
       var filter_assign = $('#filter_assign').val();
       var logger = $('#logger').val();
       var view = $('#view').val();

       if(filter_status != '' || filter_assign != '' || logger !='' || view !='')
       {
        $('#dataTable2').DataTable().destroy();
        fill_datatable(filter_status, filter_assign, logger, view);
       }
       else
       {
        $('#dataTable2').DataTable().destroy();
        fill_datatable();
       }
      });

      $(document).on("click", "#filter", function(){
       var filter_status = $('#filter_status').val();
       var filter_assign = $('#filter_assign').val();
       var datetimepicker1 = $('#datetimepicker1').val();
       var datetimepicker2 = $('#datetimepicker2').val();
       var logger = $('#logger').val();
       var view = $('#view').val();

       if(filter_status != '' || filter_assign != '' || datetimepicker1 != '')
       {
        $('#dataTable2').DataTable().destroy();
        fill_datatable(filter_status, filter_assign, logger, view, datetimepicker1, datetimepicker2);
       }
       else
       {
        $('#dataTable2').DataTable().destroy();
        fill_datatable();
       }
      });
    
      
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