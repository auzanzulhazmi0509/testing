@extends('pages.users.master-layout')

@section('title')
    iCLOP | Dashboard
@endsection

@section('content-header')
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <p class="m-0"> Dashboard </p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <!-- Main content -->
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="card card-primary card-outline">
                        <div class="card-body">
                            <h5 class="card-title"><strong>DML Learning</strong></h5>
                            <p class="card-text">
                                Welcome to iCLOP DML Database Learning Platform.
                            </p>
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
                <div class="col-md-6">
                    <div class="editor" id="editor" style="height: 200px;">SELECT * FROM student where grade > 80 and name = ''John'' order by grade asc</div>
                    <div class="row mt-3">
                        <div class="col-3">
                            <button id="runButton" class="btn btn-success w-100" data-toggle="tooltip"
                                data-placement="bottom" title="Run"><i class="fa fa-play"></i></button>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div id="output" class="row">Outout shown here</div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- /.content -->
@endsection

@section('script')
<script>
    editor = ace.edit("editor");
    editor.setTheme("ace/theme/monokai");
    editor.session.setMode("ace/mode/pgsql");
</script>

<script>
    $('#runButton').click(function(){
        if (editor.getSession().getValue() == "") {
            alert("Silakan tulis jawaban anda terlebih dahulu!");
        } else {
            $.ajax({
                url: "{{ route('u.executecode') }}",
                method: "POST",
                data: {
                    code: editor.getSession().getValue(),
                },
                success: function(response) {
                    // alert(editor.getSession().getValue());
                    $("#output").html(response.result);
                },
                error: function() {
                    $(".output").html("Something went wrong!");
                }
            });
        }
    })
</script>
@endsection
