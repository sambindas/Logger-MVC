@extends('support.layout.main')
@section('title') Facility @endsection
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
                                <li><span>Facilities</span></li>
                                <li><span></span></li>
                                <li><span></span></li>
                                <li><button id="newissue" class="btn btn-primary btn-flat" data-toggle="modal" data-target=".newfacility">New Facility</button></li>
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

            <div class='modal fade' id='switch'>
                <div class='modal-dialog modal-notify modal-primary modal-notify modal-primary modal-sm'>
                    <div class='modal-content'>
                        <div class='modal-header'>
                            <h5 class='heading lead'>Switch States</h5>
                            <button type='button' class='close' data-dismiss='modal'><span>&times;</span></button>
                        </div>
                        <div class='modal-body'>
                            <p>Currently Viewing <?php echo '<b>'.$state_name.'</b>'; ?></p><br><br>
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
                                <h4 class="header-title">Facilities</h4>
                                <div class="data-tables datatable-primary">
                                    <div id="my_table">
                                        <table id="dataTable2" class="text-center table table-hover">
                                            <thead class="text-capitalize">
                                                <tr>
                                                    <th>Facility Name</th>
                                                    <th>State</th>
                                                    <th>Contact Person</th>
                                                    <th>Contact Person Phone</th>
                                                    <th>Email</th>
                                                    <th>Local IP</th>
                                                    <th>Online URL</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $il = mysqli_query($conn, "SELECT * from facility");
                                                $sn = 1;
                                                while ($li_row = mysqli_fetch_array($il)) {
                                                $state_id = $li_row['state_id'];
                                                $l = mysqli_query($conn, "SELECT * from state where id='$state_id'");
                                                while ($l_row = mysqli_fetch_array($l)) { $state_name = $l_row['state_name']; }
                                                ?>
                                                <tr>
                                                    <td><?php echo $li_row['name'] ; ?></td>
                                                    <td><?php echo $state_name ; ?></td>
                                                    <td><?php echo $li_row['contact_person'] ; ?></td>
                                                    <td><?php echo $li_row['contact_person_phone'] ; ?></td>
                                                    <td><?php echo $li_row['email'] ; ?></td>
                                                    <td><?php echo $li_row['server_ip'] ; ?></td>
                                                    <td><?php echo $li_row['online_url'] ; ?></td>
                                                    <td><div class="dropdown">
                                                            <button class="btn btn-xs btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            Action
                                                            </button>
                                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                                <?php
                                                                    echo '<a data-toggle="modal" data-target="#edt'.$li_row["id"].'" class="dropdown-item" href="#">Edit</a>';
                                                                    echo '<a data-toggle="modal" data-target="#cst'.$li_row["id"].'" class="dropdown-item" href="#">Change State</a>';
                                                                    echo '<a data-toggle="modal" data-target="#del'.$li_row["id"].'" class="dropdown-item" href="#">Delete</a>';
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>

                                            <!-- edit modal start -->
                                            <div class="modal fade" id="edt<?php echo $li_row['id']; ?>">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Edit Facility</h5>
                                                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <!-- login area start -->
                                                            <div class="login-area">
                                                                <div class="container">
                                                                        <form action="processing.php" method="post">
                                                                            <div class="login-form-head">
                                                                                <p id="formErr"></p>
                                                                            </div>
                                                                            <div class="login-form-body">
                                                                                <div class="form-gp">
                                                                                    <input type="text" placeholder="Enter Facility Code" name="fcode" value="<?php echo $li_row['code']; ?>" required>
                                                                                    
                                                                                    <div id="errfc"></div>
                                                                                </div>
                                                                                <div class="form-gp">
                                                                                    <input type="text" name="fname" placeholder="Facility Name" value="<?php echo $li_row['name']; ?>" required>
                                                                                    
                                                                                    <div id="errfn"></div>
                                                                                </div>
                                                                                <div class="form-gp">
                                                                                    <input type="text" name="cperson" placeholder="Contact Person" value="<?php echo $li_row['contact_person']; ?>">
                                                                                    
                                                                                    <div id="errfn"></div>
                                                                                </div>
                                                                                <div class="form-gp">
                                                                                    <input type="text" name="cpersonp" placeholder="Contact Person's Phone" value="<?php echo $li_row['contact_person_phone']; ?>">
                                                                                    
                                                                                    <div id="errfn"></div>
                                                                                </div>
                                                                                <div class="form-gp">
                                                                                    <input type="email" name="email" placeholder="Email" value="<?php echo $li_row['email']; ?>">
                                                                                    
                                                                                    <div id="errfn"></div>
                                                                                </div>
                                                                                <div class="form-gp">
                                                                                    <input type="text" name="server_ip" value="<?php echo $li_row['server_ip']; ?>" placeholder="Local IP">
                                                                                    
                                                                                    <div id="errfn"></div>
                                                                                </div>
                                                                                <div class="form-gp">
                                                                                    <input type="text" name="online_url" placeholder="Online URL" value="<?php echo $li_row['online_url']; ?>">
                                                                                    
                                                                                    <div id="errfn"></div>
                                                                                </div>
                                                                                <input type="hidden" name="id" value="<?php echo $li_row['id']; ?>">
                                                                                <input type="hidden" name="url" value="<?php echo $url; ?>"><br>
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

                                            <!-- Change STate modal start -->
                                            <div class="modal fade" id="cst<?php echo $li_row['id']; ?>">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-body">
                                                            <!-- login area start -->
                                                            <div class="login-area">
                                                                <div class="container">
                                                                        <form action="" method="post">
                                                                            <div class="login-form-body">
                                                                                <div class="form-gp">
                                                                                    <select name="state_id" id="states" class="custom-select border-0 pr-3" required>
                                                                                        <option value="" selected="">Select State</option>
                                                                                        <?php
                                                                                        $fc = mysqli_query($conn, "SELECT * from state");
                                                                                        while ($fc_row = mysqli_fetch_array($fc)) {
                                                                                            echo '<option value="'.$fc_row['id'].'">'.$fc_row['state_name'].'</option>';
                                                                                        }
                                                                                        ?>
                                                                                    </select>
                                                                                    <div id="errfn"></div>
                                                                                </div>
                                                                                <input type="hidden" name="id" value="<?php echo $li_row['id']; ?>">
                                                                                <input type="hidden" name="url" value="<?php echo $url; ?>"><br>
                                                                                <div class="submit-btn-area">
                                                                                    <input class="btn btn-primary" name="submit_cst" type="submit" value="Submit">
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

                                            <!-- delete modal start -->
                                            <div class="modal fade" id="del<?php echo $li_row['id']; ?>">
                                                <div class="modal-dialog modal-sm">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Delete Facility</h5>
                                                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Are You Sure?</p>
                                                            <form method="post" action="">
                                                                <input type="hidden" name="id" value="<?php echo $li_row['id']; ?>">
                                                                <br><button type="submit" class="btn btn-primary" name="delete_f">Delete</button>
                                                            </form><br>
                                                        </div>
                                                        <div class="modal-footer">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Small modal modal end -->
                                            <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Primary table end -->

                    <!-- Large modal start -->
                    <!-- Large modal -->
                    <div class="newissue modal fade bd-example-modal-lg">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Add Facility</h5>
                                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                </div>
                                <div class="modal-body">

                                    <!-- login area start -->
                                    <div class="login-area">
                                        <div class="container">
                                                <form action="javascript:;">
                                                    <div class="login-form-head">
                                                        <h4>Add Facility</h4>
                                                        <p id="formErr"></p>
                                                    </div>
                                                    <div class="login-form-body">
                                                        <div class="form-gp">
                                                            <label for="exampleInputName1">Facility Code</label>
                                                            <input type="text" id="fcode" required>
                                                            <i class="ti-user"></i><br>
                                                            <div id="errfc"></div>
                                                        </div>
                                                        <div class="form-gp">
                                                            <label for="exampleInputName1">Facility Name</label>
                                                            <input type="text" id="fname" required>
                                                            <i class="ti-user"></i><br>
                                                            <div id="errfn"></div>
                                                        </div>
                                                        <div class="form-gp">                                                            
                                                            <select name="state" id="state" class="custom-select border-0 pr-3" required>
                                                                <option value="" selected="">Select State</option>
                                                                <?php
                                                                $fc = mysqli_query($conn, "SELECT * from state");
                                                                while ($fc_row = mysqli_fetch_array($fc)) {
                                                                    echo '<option value="'.$fc_row['id'].'">'.$fc_row['state_name'].'</option>';
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-gp">
                                                            <label for="exampleInputName1">Contact Person</label>
                                                            <input type="text" id="cperson" required>
                                                            <i class="ti-user"></i><br>
                                                            <div id="errfn"></div>
                                                        </div>
                                                        <div class="form-gp">
                                                            <label for="exampleInputName1">Contact Person Phone</label>
                                                            <input type="text" id="cpersonp" required>
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
                                                            <label for="exampleInputName1">Local IP</label>
                                                            <input type="text" id="serverip">
                                                            <i class="ti-user"></i><br>
                                                            <div id="errfn"></div>
                                                        </div>
                                                        <div class="form-gp">
                                                            <label for="exampleInputName1">Online URL</label>
                                                            <input type="text" id="online_url">
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
    <script type="text/javascript">
        $(document).ready(function(){
            
            $('#form_submit').click(function(){

                var state = $('#state').val();
                var name = $('#fname').val();
                var code = $('#fcode').val();
                var cperson = $('#cperson').val();
                var cpersonp = $('#cpersonp').val();
                var serverip = $('#serverip').val();
                var online_url = $('#online_url').val();
                var email = $('#email').val();
                console.log(state);
                if (name == '' || code == '' || cperson == '' || cpersonp == '' || state == '') {
                    $('#formErr').html('<span class="alert alert-danger">Please Fill Required Fields</span>');
                    return false;
                }
                else if (name != '' || code != '' || cperson != '' || cpersonp != '' || state !== '') {
                    $('#formErr').html('');

                    var datastring = 'code='+code;

                    $.ajax({
                        url: 'ajax/code.php',
                        method: 'post',
                        data: datastring,
                        success: function(msg) {
                            if (msg == 1) {
                                $('#errfc').html('<div class="alert alert-danger"><p>Facility Exists</p></div>');

                                return false;

                            } else {
                                $('#errem').html('');
                                registerFinal();
                            }
                        }
                    });

                    var datastringg = 'state='+state+'&name='+name+'&code='+code+'&cperson='+cperson+'&cpersonp='+cpersonp+'&serverip='+serverip+'&online_url='+online_url+'&email='+email;

                    function registerFinal() {

                    $.ajax({
                        url: 'ajax/fac.php',
                        method: 'post',
                        data: datastringg,
                        success: function(msg) {
                            if (msg == 1) {
                                window.location.replace('facility.php');
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
    
    <script type="text/javascript">
        var dataTable = $('#dataTable2').DataTable({});
    </script>

</html>