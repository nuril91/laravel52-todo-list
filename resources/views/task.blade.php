<!DOCTYPE html>
<html>
    <head>
        <title>Todo List</title>

        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">

        <!-- Bootstrap -->
        <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    </head>
    <body>
        <div class="container">
            <h3 class="text-center">Todo List For Me!</h3>

            <div class="card">
                <div class="card-header">My Todo List</div>
                <div class="card-body">
                    {!! Form::open(array('method'=>'post', 'id'=>'task-form', 'class' => 'form-inline')) !!}
                        <div class="col-md-6">
                            <input type="text" class="form-control mb-2 mr-sm-2" id="task_detail" name="task_detail" style="width: 100%;">
                        </div>

                        <button type="submit" class="btn btn-primary mb-2" id="submit">Add Todo</button>
                    {!! Form::close() !!}
                    <div id="error"></div>

                    <br>

                    <ul class="list">
                        @foreach($tasks as $task)
                            @php($style = '')
                            @if($task->task_status == 'done')
                                @php($style = 'text-decoration: line-through')
                            @endif
                                <div class="col-md-12">
                                    <li style="{{ $style }}" class="list-group-item col-md-11 float-left list-group-item-action" id="list-group-item-{{ $task->id }}" data-id="{{ $task->id }}">
                                        {{ $task->task_detail }}
                                    </li>
                                    <span style="cursor:pointer;" class="badge badge-dark float-right" id="badge-{{ $task->id }}" data-id="{{ $task->id }}">X</span>
                                </div>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="{{ asset('js/bootstrap.min.js') }}"></script>

        <script>
            $(document).ready(function(){

                // submit task
                $(document).on('submit', '#task-form', function(event){
                    event.preventDefault();

                    if($('#task_detail').val() == '')
                    {
                        $('#error').html('<div class="alert alert-danger" role="alert">Enter Your Task</div>');
                        return false;
                    }
                    else
                    {
                        $('#error').hide();
                        $('#submit').attr('disabled', 'disabled');
                        $.ajax({
                            url: '/add-task',
                            method: "post",
                            data: $(this).serialize(),
                            success:function(result)
                            {
                                var data = result.data;
                                $('#submit').attr('disabled', false);
                                $('#task-form')[0].reset();

                                var list = '<div class="col-md-12">' +
                                    '<li class="list-group-item col-md-11 float-left list-group-item-action" id="list-group-item-'+ data.id +'" data-id="'+ data.id +'">'+ data.task_detail + '</li>' +
                                '<span style="cursor:pointer;" id="badge-'+ data.id +'" class="badge badge-dark float-right" data-id="'+ data.id +'">X</span>' +
                                '</div>';
                                $('.list').prepend(list);
                            }
                        })
                    }
                });

                // update task to done
                $(document).on('click', '.list-group-item', function(){
                    var task_id = $(this).data('id');
                    var data = {
                        "_token":$("input[name=_token]").val(),
                    };
                    $.ajax({
                        url: '/update-task/' + task_id,
                        method: "put",
                        data: data,
                        success:function(result)
                        {
                            var data = result.data;

                            if (data.task_status == 'done') {
                                $('#list-group-item-'+task_id).css('text-decoration', 'line-through');
                            } else {
                                $('#list-group-item-'+task_id).css('text-decoration', '');
                            }
                        }
                    })
                });

                // delete task
                $(document).on('click', '.badge', function(){
                    var task_id = $(this).data('id');
                    var data = {
                        "_token":$("input[name=_token]").val(),
                    };
                    $.ajax({
                        url: '/delete-task/' + task_id,
                        method: "delete",
                        data: data,
                        success:function(data)
                        {
                            $('#list-group-item-'+task_id).fadeOut('slow');
                            $('#badge-'+task_id).fadeOut('slow');
                        }
                    })
                });
            });
        </script>
    </body>
</html>
