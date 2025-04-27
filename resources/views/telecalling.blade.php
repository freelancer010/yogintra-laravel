@extends('layouts.layout')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Telecalling</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Telecalling</li>
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
                            <div class="row align-items-center ml-auto" style="margin-bottom:-2px">
                                <div class="filter d-flex justify-content-center align-items-center">
                                    <div class="d-flex mr-1 align-items-center">
                                        <button type="button" class="btn btn-sm btn-success mr-3 " onclick=filter()>
                                            Generate&nbsp;&nbsp;<i class="fas fa-arrow-right"></i>
                                        </button>
                                    </div>
                                    <div class="d-flex mr-1 align-items-center">
                                        <input style="height: 32px;" type="date" class="form-control mr-3" id="fromDate"
                                            max="<?php echo date('Y-m-d'); ?>">
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <label for="toDate" class="exampleInputEmail1 mt-1 mr-3 text-muted">To</label>
                                        <input style="height: 32px;" type="date" class="form-control mr-1" id="toDate"
                                            max="<?php echo date('Y-m-d'); ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th style="width:2% !important;">ID</th>
                                        <th style="width:10% !important;">Client Name</th>
                                        <th style="width:6% !important;">Client Number</th>
                                        <th style="width:5% !important;">Country</th>
                                        <th style="width:5% !important;">State</th>
                                        <th style="width:5% !important;">City</th>
                                        <th style="width:10% !important;">Type Of Class</th>
                                        <th style="width:10% !important;">Date</th>
                                        <th style="width:5% !important;">Status</th>
                                        <th style="width:5% !important;">Action</th>
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

    let filter = () => {
        let toDate = $("#toDate").val();
        let fromDate = $("#fromDate").val();
        getData(fromDate, toDate);
    }

    let reset = () => {
        let toDate = $("#toDate").val('');
        let fromDate = $("#fromDate").val('');
        getData();
    }

    let getData = (startDate = '', endDate = '') => {
        var apiUrl = PANELURL + 'telecalling/view';
        ajaxCallData(apiUrl, {
                startDate: startDate,
                endDate: endDate
            }, 'POST')
            .then(function(resp) {
                if (resp.success == 1) {
                    response = resp.data;
                    let cols = [{
                            data: null,
                            render: function(data, type, row) {
                                return row.id;
                            }
                        },
                        {
                            data: null,
                            render: function(data, type, row) {
                                return `<a href="${PANELURL}telecalling/profile?id=${row.id}">${row.name}</a>`;
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
                                return `<div justify-content-center">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" onclick="change_status(${row.id})" id="customSwitch${row.id}">
                                            <label title="change status to customers" style="cursor:pointer" class="custom-control-label" for="customSwitch${row.id}"></label>
                                        </div>
                                    </div>`;
                            }
                        },
                        {
                            data: null,
                            render: function(data, type, row) {
                                return `<div class="d-flex justify-content-between">
                                            <a href="lead/edit?id=${row.id}" title="edit" class="btn btn-warning btn-xs mr5">
                                                <i class="fa fa-edit mr5"></i>
                                            </a>
                                            <button href="#" title="delete this row" onclick="deleteTelecalling(${row.id})" class="btn btn-danger btn-xs">
                                                <i class="fa fa-trash mr5"></i>
                                            </button>
                                            <button title="change status to leads" onclick="change_back_toLeads(${row.id})" class="btn btn-success btn-xs mr5">
                                                <i class="fa fa-reply mr5"></i>
                                            </button>
                                        </div>`;
                            }
                        }
                    ]
                    createDataTable("example1", response, cols);
                    $('.buttons-pdf, .buttons-csv').css('height', '33px');

                } else {
                    createDataTable("example1", '', '');
                }
            })
            .catch(function(err) {
                console.log(err);
            });
    };

    getData();

    let change_status = (id) => {
        let postData = {
            'id': id
        }
        ajaxCallData(PANELURL + 'telecalling/changeStatus', postData, 'POST')
            .then(function(resp) {
                if (resp.success == 1) {
                    notifyAlert(resp.message, 'success');
                } else {
                    notifyAlert('You are not authorized!', 'danger');
                }
                getData();
            })
            .catch(function(err) {
                console.log(err);
            });
    };

    let change_back_toLeads = (id) => {
        let postData = {
            'id': id
        }
        ajaxCallData(PANELURL + 'telecalling/changeStatusToLeads', postData, 'POST')
            .then(function(resp) {
                if (resp.success == 1) {
                    getData();
                    notifyAlert(resp.message, 'success');
                } else {
                    notifyAlert('You are not authorized!', 'danger');
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
        ajaxCallData(PANELURL + 'telecalling/delete', postData, 'POST')
            .then(function(resp) {
                if (resp.success == 1) {
                    getData();
                    notifyAlert('Deleted successfully!', 'success');
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