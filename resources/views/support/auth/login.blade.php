@extends('support.layout.login')
@section('title') Login @stop
@Section('content')
    <!-- preloader area end -->
    <!-- login area start -->
    <div class="login-area">
        <div class="container">
            <div class="login-box ptb--100">
                <form action="javascript:;">
                    <div class="login-form-head">
                        <img src="{{asset('images/logo.jpeg')}}"><br><br>
                        <p>Sign in to use the Incident log</p><br>
                        <p><?php 
                        if (isset($_SESSION['msg'])) {
                            echo $_SESSION['msg'];
                            unset($_SESSION['msg']);
                        }
                        ?></p>
                        <p id="formErr"></p>
                    </div>
                    <div class="login-form-body">
                        <div class="form-gp">
                            <label for="exampleInputEmail1">Email address</label>
                            <input type="email" id="email">
                            <i class="ti-email"></i>
                        </div>
                        <div class="form-gp">
                            <label for="exampleInputPassword1">Password</label>
                            <input type="password" id="password">
                            <i class="ti-lock"></i>
                        </div>
                        <div class="row mb-4 rmber-area">
                            <div class="col-6 text-right">
                                <a href="reset-pass.php">Forgot Password?</a>
                            </div>
                        </div>
                        <input type="hidden" id="user_type" name="user_type" value="eclat">
                        <div class="submit-btn-area">
                            <input value="Submit" id="form_submit" class="btn btn-primary" type="submit">
                            <div id="loade"></div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- login area end -->
@stop
@section('js')
<script type="text/javascript">

    $(document).ready(function(){
        $('#form_submit').click(function(){
            $(this).fadeOut();
            $('#loade').html('<img src="assets/images/eclipse.gif">');
            var email = $('#email').val();
            var password = $('#password').val();
            var user_type = $('#user_type').val();

            if (email == '' || password == '') {
                $('#formErr').html('<span class="alert alert-danger">Please Fill In All Fields</span>');
                $(this).fadeIn();
            $('#loade').html('');
                return false;
            } else {

                $('#formErr').html('');


                var datastring = 'email='+email+'&password='+password+'&user_type='+user_type;
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '/login',
                    method: 'post',
                    data: datastring,
                    success: function(msg){
                        console.log(msg)
                        if (msg == 'support') {
                            window.location.replace('/');
                        } else if (msg == 'client') {
                            window.location.replace('clientindex.php');
                        } else {
                            $('#formErr').html('<span class="alert alert-danger">Authentication Failed!</span>');
                            $('#form_submit').fadeIn();
                            $('#loade').html('');
                            return false;
                        }
                    }
                });
            }
        });
    });
</script>
@stop