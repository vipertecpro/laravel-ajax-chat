@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Dashboard</div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>
                                                #
                                            </th>
                                            <th>
                                                ID
                                            </th>
                                            <th>
                                                Username
                                            </th>
                                            <th>
                                                Email
                                            </th>
                                            <th>
                                                Action
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($users as $key => $value)
                                            <tr>
                                                <td>{{ $key = $key + 1   }}</td>
                                                <td>{{ $value->id }}</td>
                                                <td>{{ $value->username }}</td>
                                                <td>{{ $value->email }}</td>
                                                <td>
                                                    <button class="btn btn-success btn-sm startChat" data-user-id = {{ $value->id }}>
                                                        Start Chat
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="pagination">
                                {{ $users->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <div id="chatContainer">
        <div id="conversation">
        </div>
        <div id="messageTextBtn">
            <div class="row">
                <div class="col-md-12">
                    <div class="input-group">
                        <input id="sendToID" name="sendToID" type="hidden">
                        <textarea id="message" class="form-control" placeholder="Enter message here . . ." aria-label="Enter message here . . ." aria-describedby="basic-addon2"></textarea>
                        <div class="input-group-append">
                            <button class="btn btn-outline-success sendMessage" type="button">Send</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="title">Start Conversation</div>
    </div>
@endsection

@section('styles')
    <style>
        #conversation .text-left {
            margin-top: 10px;
            margin-bottom: 10px;
        }
        #conversation .text-right {
            margin-top: 10px;
            margin-bottom: 10px;
        }
        #conversation p {
            display: inline-block;
            background-color: gray;
            color: #fff;
            padding: 5px;
            width: 50%;
            text-align: justify;
            border-radius: 5px;
        }
        #chatContainer {
            width: 300px;
            height: 40px;
            border: 1px solid #e1e1e1;
            position: fixed;
            right: 20px;
            bottom: 0;
            z-index: 9999;
            background-color: #fff;
            box-shadow: 1px 2px 5px black;
            cursor: pointer;
        }
        #chatContainer .title {
            position: absolute;
            bottom: 0;
            border-top: 0px solid black;
            height: 40px;
            width: 100%;
            padding: 10px;
            display: block;
            left: 0;
            right: 0;
            background-color: lightgray;
            font-weight: 800;
        }
        #conversation {
            display: none;
            width: 100%;
            height: 277px;
            border: 0px solid #171717;
            position: absolute;
            top: 0px;
            z-index: 2;
            padding: 10px;
            overflow-y: scroll;
            overflow-x: hidden;
            left: 0;
            right: 0;
            margin: 0;
        }
        #messageTextBtn {
            display: none;
            position: absolute;
            width: 100%;
            bottom: 40px;
            padding: 0;
        }
        #messageTextBtn textarea{
            resize: none;
        }
        #messageTextBtn button {
            border-radius: 0px;
        }
    </style>
@endsection

@section('scripts')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        function renderList(getUserSendToId){
            jQuery.ajax({
                url: "{{ route('renderConversationList') }}",
                method: 'post',
                data: {
                    getUserSendToId: getUserSendToId
                },
                success: function(result){
                    $('#chatContainer #conversation')
                        .html(result.html)
                        .scrollTop($("#chatContainer #conversation")[0].scrollHeight);
                },
                error: function(result){
                    console.log(result);
                }
            });
        }
        $(document).click(function(evt){
            if($(evt.target).closest(".startChat").length > 0){
                return false;
            }
            if($(evt.target).closest("#chatContainer").length > 0){
                return false;
            }
            $('#chatContainer').animate({height:40},200).removeClass('hide');
            $('#conversation').css('display','none');
            $('#messageTextBtn').css('display','none');
        });
        $(document).on('click','.startChat',function(){
            let container = $('#chatContainer');
            if(container.hasClass('hide')) {
                container.animate({height:40},200).removeClass('hide');
                $('#conversation').css('display','none');
                $('#messageTextBtn').css('display','none');
            } else {
                container.animate({height:400},200).addClass('hide');
                $('#conversation').css('display','block');
                $('#messageTextBtn').css('display','block');
            }
            let getUserSendToId = $(this).attr('data-user-id');
            $('#sendToID').val(getUserSendToId)
            renderList(getUserSendToId);
        });

        $(document).on('click','.sendMessage',function(){
            let getUserSendToId = $('#sendToID').val();
            let message = $('#chatContainer #message').val();
            jQuery.ajax({
                url: "{{ route('sendMessage') }}",
                method: 'post',
                data: {
                    getUserSendToId: getUserSendToId,
                    message: message,
                },
                success: function(result){
                    console.log(result);
                    $('#chatContainer #message').val('');
                    renderList(getUserSendToId);
                },
                error: function(result){
                    console.log(result);
                }
            });
        });
    </script>
@endsection
