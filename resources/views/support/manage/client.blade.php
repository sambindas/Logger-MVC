@extends('support.layout.main')
@section('title') Client Users @endsection
@section('content')
            <!-- page title area start -->
            <div class="page-title-area">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <div class="breadcrumbs-area clearfix">
                            <h4 class="page-title pull-left">Dashboard</h4><br><br>
                            <ul class="breadcrumbs pull-left">
                                <li><a href="/">Home</a></li>
                                <li><span>Manage / </span></li>
                                <li><span>Client Users</span></li>
                                <li><span></span></li>
                                <li><span></span></li>
                                <li><button id="newissue" class="btn btn-primary btn-flat" data-toggle="modal" data-target=".newuser">New Client</button></li>
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
                            <h4 class="user-name dropdown-toggle" data-toggle="dropdown">{{ Session::get('name')}} <i class="fa fa-angle-down"></i></h4>
                            <div class="dropdown-menu">
                                <a data-toggle='modal' data-target="#switch" class="dropdown-item" href="settings.php">Switch States</a>
                                <a class="dropdown-item" href="/changepassword">Change Password</a>
                                <a class="dropdown-item" href="/settings">Settings</a>
				                <a class="dropdown-item" href="/help">Help</a>
                                <a class="dropdown-item" href="/logout">Log Out</a>
                            </div>
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
                                <h4 class="header-title">Users</h4>
                                <div class="data-tables datatable-primary">
                                    <div id="my_table">
                                        <table id="dataTable2" class="text-center table table-hover">
                                            <thead class="text-capitalize">
                                                <tr>
                                                    <th>Contact Person</th>
                                                    <th>Facility Name</th>
                                                    <th>Email</th>
                                                    <th>Phone</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($users as $user)
                                                <tr>
                                                    <td>{{$user->user_name}}</td>
                                                    <td>{{$user->name}}</td>
                                                    <td>{{$user->user_email}}</td>
                                                    <td>{{$user->phone}}</td>
                                                    <td><div class="dropdown">
                                                            <button class="btn btn-xs btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            Action
                                                            </button>
                                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                            <a data-toggle="modal" data-target="#edit{{$user->id}}" class="dropdown-item" href="#">Edit</a>
                                                            @if($user->status == 1)
                                                                <a data-toggle="modal" data-target="#deactivate{{$user->id}}" class="dropdown-item" href="#">Deactivate</a>
                                                            @endif
                                                            @if($user->status == 0)
                                                                <a data-toggle="modal" data-target="#activate{{$user->id}}" class="dropdown-item" href="#">Activate</a>
                                                            @endif
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>

                                            <!-- activate modal start -->
                                            <div class="modal fade" id="activate{{$user->id}}">
                                                <div class="modal-dialog modal-sm">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Activate User</h5>
                                                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Are You Sure?</p>
                                                            <form method="post" action="/activate">
                                                            @csrf
                                                                <input type="hidden" name="id" value="{{$user->id}}">
                                                                <br><button type="submit" class="btn btn-primary" name="delete_f">Activate</button>
                                                            </form><br>
                                                        </div>
                                                        <div class="modal-footer">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Small modal modal end -->

                                            <!-- delete modal start -->
                                            <div class="modal fade" id="deactivate{{$user->id}}">
                                                <div class="modal-dialog modal-sm">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Deactivate User</h5>
                                                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Are You Sure?</p>
                                                            <form method="post" action="/deactivate">
                                                            @csrf
                                                                <input type="hidden" name="id" value="{{$user->id}}">
                                                                <br><button type="submit" class="btn btn-primary" name="delete_f">Deactivate</button>
                                                            </form><br>
                                                        </div>
                                                        <div class="modal-footer">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- edit modal start -->
                                            <div class="modal fade" id="edit{{$user->id}}">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Edit Credentials</h5>
                                                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <!-- login area start -->
                                                            <div class="login-area">
                                                                <div class="container">
                                                                        <form action="" method="post">
                                                                            <div class="login-form-head">
                                                                                <p id="formErr">Edit Credentials</p>
                                                                            </div>
                                                                            <div class="login-form-body">
                                                                                <div class="form-gp">
                                                                                    <input type="text" placeholder="Enter Facility Code" name="fcode" value="{}" required>
                                                                                    
                                                                                    <div id="errfc"></div>
                                                                                </div>
                                                                                <div class="form-gp">
                                                                                    <input type="text" name="fname" placeholder="Client Name" value="{{$user->user_name}}" required>
                                                                                    
                                                                                    <div id="errfn"></div>
                                                                                </div>
                                                                                <div class="form-gp">
                                                                                    <input type="email" name="email" placeholder="Email" value="{{$user->facility_email}}">
                                                                                    
                                                                                    <div id="errfn"></div>
                                                                                </div>
                                                                                <div class="form-gp">
                                                                                    <input type="text" name="phone" placeholder="Phone Number" value="{{$user->phone}}">
                                                                                    
                                                                                    <div id="errfn"></div>
                                                                                </div>
                                                                                <input type="hidden" name="id" value="{{$user->id}}">
                                                                                <div class="submit-btn-area">
                                                                                    <input class="btn btn-primary" name="submit_edt" type="submit" value="Submit">
                                                                                </div>
                                                                            </div>
                                                                        </form>
                                                                </div>
                                                            </div>
                                                            <!-- login area end -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Primary table end -->

                    <!-- Large modal -->
                    <div class="newuser modal fade bd-example-modal-lg">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Add Credentials</h5>
                                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                </div>
                                <div class="modal-body">

                                    <!-- login area start -->
                                    <div class="login-area">
                                        <div class="container">
                                                <form action="javascript:;">
                                                    <div class="login-form-head">
                                                        <h4>Add Credentials</h4>
                                                        <p id="formErr"></p>
                                                    </div>
                                                    <div class="login-form-body">
                                                        <div class="form-gp">
                                                            <select id="scode" class="custom-select border-0 pr-3" required>
                                                                <option value="" selected="">Select State</option>
                                                                @foreach($states as $state)
                                                                    <option value="{{$state->id}}">{{$state->state_name}}</option>';
                                                                @endforeach
                                                            </select>
                                                            <div id="errfc"></div>
                                                        </div>
                                                        <div class="form-gp">
                                                            <select id="fcode" class="custom-select border-0 pr-3" required>
                                                                <option value="" selected="">Select State First</option>
                                                            </select>
                                                            <div id="errfc"></div>
                                                        </div>
                                                        <div class="form-gp">
                                                            <label for="exampleInputName1">Contact Person</label>
                                                            <input type="text" id="name" required>
                                                            <i class="ti-user"></i><br>
                                                            <div id="errfn"></div>
                                                        </div>
                                                        <div class="form-gp">
                                                            <label for="exampleInputName1">Email</label>
                                                            <input type="text" id="email" required>
                                                            <i class="ti-user"></i><br>
                                                            <div id="errfn"></div>
                                                        </div>
                                                        <div class="form-gp">
                                                            <label for="exampleInputName1">Phone</label>
                                                            <input type="text" id="phone" required>
                                                            <i class="ti-phone"></i><br>
                                                            <div id="errfn"></div>
                                                        </div>
                                                        <div class="form-gp">
                                                            <label for="exampleInputName1">Password</label>
                                                            <input type="text" id="password" required>
                                                            <i class="ti-user"></i><br>
                                                            <div id="errfn"></div>
                                                        </div>
                                                        <div class="submit-btn-area">
                                                            <input class="btn btn-primary" id="form_submit" type="submit" value="Submit">
                                                        </div>
                                                    </div>
                                                </form>
                                        </div>
                                    </div>
                                    <!-- login area end -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Large modal modal end -->
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
    <!-- Start datatable js -->
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
    <script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>
    <script type="text/javascript">
        var dataTable = $('#dataTable2').DataTable({});
    </script>
    <script type="text/javascript">
        $(document).ready(function(){
                
            $('#form_submit').click(function(){

                var name = $('#name').val();
                var email = $('#email').val();
                var facility = $('#fcode').val();
                var phone = $('#phone').val();
                var password = $('#password').val();
                var state = $('#scode').val();

                if (name == '' || email == '' || phone == '' || password == '' || state == '') {
                    $('#formErr').html('<span class="alert alert-danger">Please Fill In All Fields</span>');
                    return false;
                }
                else if (name != '' || email != '' || phone != '' || password != '' || state != '') {
                    $('#formErr').html('');

                    var datastring = 'email='+email;

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: '/checkEmail',
                        method: 'post',
                        data: datastring,
                        success: function(msg) {
                            if (msg == 0) {
                                $('#errem').html('<div class="alert alert-danger"><p>Another User Exists With That Email</p></div>');
                                return false;
                            } else {
                                $('#errem').html('');
                                registerFinal();
                            }
                        }
                    });

                    var datastring = 'type=client'+'&name='+name+'&email='+email+'&phone='+phone+'&password='+password+'&facility='+facility+'&state='+state;

                    function registerFinal() {
                    $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                    });
                    $.ajax({
                        url: '/register',
                        method: 'post',
                        data: datastring,
                        success: function(msg) {
                            if (msg == 1) {
                                window.location.replace('/client');
                            }else {
                                $('#loaderxy').html('<span class="alert alert-danger">Something Went wrong. Please try again</span>');
                            }
                        }
                    });
                }
                }
            });
        });
    </script>
    <script>
    $(document).ready(function(){
        $('#scode').on('change',function(){
        var state_id = $(this).val();
        if(state_id){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type:'GET',
                url:'/getFacility',
                data:'state_id='+state_id,
                success:function(html){
                    $('#fcode').html(html);
                }
            }); 
        }else{
            $('#fcode').html('<option value="">Select State first</option>');
        }
    });
    });
    </script>
@endsection
</html>