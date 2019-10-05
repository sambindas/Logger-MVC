@extends('support.layout.main')
@section('title') eClat Healthcare Incident Log - Upload Image @endsection
@section('content')

<br>
                    <div class="container">
                        <p><span class="alert alert-warning"><b>Note:</b> Image must not be more than 1mb&nbsp;&nbsp;<br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Only <b>Jpg,</b> <b>Jpeg,</b><b>Png</b> and <b>Gif</b> are allowed</span></p><br>
                            @if(count($errors)>0)
                                <div class="alert alert-danger">
                                Validation Errors<br><br>
                                <ul>
                                    @foreach($errors->all() as $error)
                                    <li>{{$error}}</li>
                                    @endforeach
                                </ul>
                                </div>
                            @endif
                            @if(Session::get('success'))
                                <div class="alert alert-success alert-block">
                                <strong>{{Session::get('success')}}</strong>
                                </div>
                            @endif
                                <br><br>
                        <form method='post' enctype='multipart/form-data' action='/incident/media/ProcessMediaUpload'>
                        @csrf
                            <div class='file_upload' id='f1'><input name='media' type='file' required /></div><br>
                            <textarea type="text" name="caption" placeholder="Insert Caption" required></textarea>
                            <!-- <div id='file_tools'>
                                <img src='assets/images/file_add.png' height="10" width="20" id='add_file' title='Add new input'/>
                                <img src='assets/images/file_del.png' height="10" width="20" id='del_file' title='Delete'/>
                            </div><br> -->
                            <br><br>

                            <input type="hidden" name="issue_id" value="{{Request()->id}}">
                            <input type='submit' class="btn btn-primary" name='submit_media' value='Upload'/>
                        </form><br> <hr>
                        @if($media)
                            @foreach($media as $image)
                            <img src="{{asset('images/media/'.$image->media_name)}}" height='500' width='500' alt="{{$image->caption}}">
                                <button class='btn btn-danger' data-toggle='modal' data-target="#del{{$image->id}}">Delete Media Entry</button>
                                <button class='btn btn-primary' data-toggle='modal' data-target="#edit{{$image->id}}">Edit Caption</button>
                                <Br><hr><br>
                            
                            <!-- edit modal start -->
                            <div class="modal fade" id="edit{{$image->id}}">
                                <div class="modal-dialog modal-sm">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit This Caption</h5>
                                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="post" action="/updateCaption">
                                            @csrf
                                                <input type="text" name="caption" value="{{$image->caption}}"><br>
                                                <input type="hidden" name="media_id" value="{{$image->id}}"><br>
                                                <br><button type="submit" class="btn btn-primary" name="edit">Edit</button>
                                            </form><br>
                                        </div>
                                        <div class="modal-footer">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- delete modal -->
                            <div class="modal fade" id="del{{$image->id}}">
                                <div class="modal-dialog modal-sm">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Delete This Image</h5>
                                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="post" action="/deleteMedia">
                                            @csrf
                                                <input type="hidden" name="media_id" value="{{$image->id}}"><br>
                                                <br><button type="submit" class="btn btn-danger" name="delete">Delete</button>
                                            </form><br>
                                        </div>
                                        <div class="modal-footer">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Small modal modal end -->
                            @endforeach
                        @endif
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

</html>