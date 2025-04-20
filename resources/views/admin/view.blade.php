@extends('layouts.layout')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>User List</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">User List</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content mt10">
        <div class="card">
            <div class="card-body">
                <div class="data_container"></div>
            </div>
        </div>
    </section>
</div>
@endsection

@section('scripts')
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    const PANELURL = "{{ url('/') }}/";

    //------------------------------------------------------------------
    function filter_data() {
        $('.data_container').html(
            '<div class="text-center"><img src="{{ asset('assets/front/css/lightbox/loading.gif') }}"/></div>'
        );

        $.post(
            "{{ url('admin/filterdata') }}",
            $('.filterdata').serialize(),
            function() {
                $('.data_container').load(
                    "{{ url('admin/list_data') }}"
                );
            }
        );
    }
    //------------------------------------------------------------------
    function load_records() {
        $('.data_container').html(
            '<div class="text-center"><img src="{{ asset('assets/front/css/lightbox/loading.gif') }}"/></div>'
        );

        $('.data_container').load("{{ url('admin/list_data') }}");
    }

    load_records();

    //---------------------------------------------------------------------
    $("body").on("change", ".tgl_checkbox", function() {
        $.post("{{ url('admin/change_status') }}", {
                _token: $('meta[name="csrf-token"]').attr('content'),
                id: $(this).data('id'),
                status: $(this).is(':checked') ? 1 : 0
            },
            function(data) {
                $.notify("Status Changed Successfully", "success");
            }
        );
    });
</script>
@endsection