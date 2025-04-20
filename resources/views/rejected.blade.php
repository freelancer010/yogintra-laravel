@extends('layouts.layout')
@section('content')

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Rejected Data</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Rejected</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header yogintra align-items-center d-flex justify-content-between">
                            <h3 class="card-title">All Rejected Data here</h3>
                            <!-- <button type="button" class="btn btn-sm btn-primary" data-toggle="modal"
                                data-target="#modal-default"><i class="fas fa-plus"></i>&nbsp;&nbsp;Add Telecallers</button> -->
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th style="width:2% !important;">ID</th>
                                        <th style="width:12% !important;">Client Name</th>
                                        <th style="width:10% !important;" s>Client Number</th>
                                        <th style="width:6% !important;">Country</th>
                                        <th style="width:6% !important;">State</th>
                                        <th style="width:6% !important;">City</th>
                                        <th style="width:15% !important;">Type Of Class</th>
                                        <th style="width:18% !important;">Date</th>
                                        <th style="width:8% !important;">Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
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

    let getData = () => {
        var apiUrl = PANELURL + 'rejected';
        ajaxCallData(apiUrl, {}, 'POST')
            .then(function(resp) {
                if (resp.success == 1) {
                    response = resp.data;
                    let cols = [{
                            data: "id"
                        },
                        {
                            data: null,
                            render: function(data, type, row) {
                                return `<a href="${PANELURL}profile?id=${row.id}">${row.name}</a>`;
                            }
                        },
                        {
                            data: "number"
                        },
                        {
                            data: "country"
                        },
                        {
                            data: "state"
                        },
                        {
                            data: "city"
                        },
                        {
                            data: "class_type"
                        },
                        {
                            data: "created_date"
                        },
                        {
                            data: null,
                            render: function(data, type, row) {
                                return `<div class="d-flex justify-content-between">
                                            <a href="profile/edit?id=${row.id}" title="edit" class="btn btn-warning btn-xs mr5">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <button href="#" title="delete this row" onclick="deleteTelecalling(${row.id})" class="btn btn-danger btn-xs">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                            <button title="change status to leads" onclick="restroreLead(${row.id})" class="btn btn-success btn-xs mr5">
                                                <i class="fa fa-reply mr5"></i>
                                            </button>
                                        </div>`;
                            }
                        }
                    ]
                    createDataTable("example1", response, cols);
                } else {
                    createDataTable("example1", '', '');
                }
            })
            .catch(function(err) {
                console.log(err);
            });
    };

    getData();

    let restroreLead = (id) => {
        let postData = {
            'id': id
        }
        ajaxCallData(PANELURL + 'AllData/restrore', postData, 'POST')
            .then(function(result) {
                jsonCheck = isJSON(result);
                if (jsonCheck == true) {
                    resp = JSON.parse(result);
                    if (resp.success == 1) {
                        getData();
                        notifyAlert(resp.message, 'success');
                    } else {
                        notifyAlert('You are not authorized!', 'danger');
                    }
                } else {
                    notifyAlert('We are sorry, You are not authorized!', 'danger');
                }
            })
            .catch(function(err) {
                console.log(err);
            });
    };

    let deleteTelecalling = (id) => {
        let postData = {
            'id': id,
        }
        ajaxCallData(PANELURL + 'telecalling/deleteData', postData, 'POST')
            .then(function(result) {
                jsonCheck = isJSON(result);
                if (jsonCheck == true) {
                    resp = JSON.parse(result);
                    if (resp.success == 1) {
                        getData();
                        notifyAlert('Deleted successfully!', 'success');
                    } else {
                        notifyAlert('You are not authorized!', 'danger');
                    }
                } else {
                    notifyAlert('You are not authorized!', 'danger');
                }

            })
            .catch(function(err) {
                console.log(err);
            });
    };
</script>
@endsection