@extends('layouts.layout')
@section('content')

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Leads</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Leads</li>
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
                            <a href="lead/add" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i>&nbsp;&nbsp;Add Leads</a>
                            <div class="row align-items-center ml-auto" style="margin-bottom:-2px">
                                <div class="filter d-flex justify-content-center align-items-center">
                                    <div class="d-flex mr-1 align-items-center">
                                        <button type="button" class="btn btn-sm btn-success mr-3 " onclick=filter()>
                                            Generate&nbsp;&nbsp;<i class="fas fa-arrow-right"></i>
                                        </button>
                                        <!-- <button type="button" class="btn btn-danger mr-3" onclick=reset()>reset</button> -->
                                    </div>
                                    <div class="d-flex mr-1 align-items-center">
                                        <!-- <label for="fromDate" class="exampleInputEmail1 mr-1 text-muted ">From</label> -->
                                        <input style="height: 32px;" type="date" class="form-control mr-3" id="fromDate" max="<?php echo date('Y-m-d'); ?>">
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <label for="toDate" class="exampleInputEmail1 mt-1 mr-3 text-muted">To</label>
                                        <input style="height: 32px;" type="date" class="form-control mr-1" id="toDate" max="<?php echo date('Y-m-d'); ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div id="loader" class="overlay hidden">
                            <i class="fas fa-2x fa-sync-alt"></i>
                        </div>
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th style="width:10% !important;">Client Name</th>
                                        <th style="width:10% !important;">Client Number</th>
                                        <th style="width:8% !important;">Country</th>
                                        <th>State</th>
                                        <th>City</th>
                                        <th>Type Of Class</th>
                                        <th style="width:90px !important;">Date</th>
                                        <th style="width:90px !important;">Time</th>
                                        <th style="width:5% !important;">Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
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
        var apiUrl = PANELURL + 'lead';
        ajaxCallData(apiUrl, {
                startDate: startDate,
                endDate: endDate
            }, 'POST')
            .then(function(resp) {
                if (resp.success == 1) {
                    response = resp.data
                    let cols = [{
                            data: null,
                            render: function(data, type, row) {
                                return row.id;
                            }
                        },
                        {
                            data: null,
                            render: function(data, type, row) {
                                return `<a href="${PANELURL}lead/profile?id=${row.id}" style="${(row.read_status == 0) ? 'font-weight:600;' :''}">${row.name}</a>`;
                            }
                        },
                        {
                            data: null,
                            render: function(data, type, row) {
                                return `<p style="${(row.read_status == 0) ? 'font-weight:600;' :''}">${row.number}</p>`;
                            }
                        },
                        {
                            data: null,
                            render: function(data, type, row) {
                                return `<p style="${(row.read_status == 0) ? 'font-weight:600;' :''}">${row.country}</p>`;
                            }
                        },
                        {
                            data: null,
                            render: function(data, type, row) {
                                return `<p style="${(row.read_status == 0) ? 'font-weight:600;' :''}">${row.state}</p>`;
                            }
                        },
                        {
                            data: null,
                            render: function(data, type, row) {
                                return `<p style="${(row.read_status == 0) ? 'font-weight:600;' :''}">${row.city}</p>`;
                            }
                        },
                        {
                            data: null,
                            render: function(data, type, row) {
                                return `<p style="${(row.read_status == 0) ? 'font-weight:600;' :''}">${row.class_type}</p>`;
                            }
                        },
                        {
                            data: null,
                            render: function(data, type, row) {
                                return `<p style="${(row.read_status == 0) ? 'font-weight:600;' :''}">${(row.created_date).slice(0,10)}</p>`;
                            }
                        },
                        {
                            data: null,
                            render: function(data, type, row) {
                                return `<p style="${(row.read_status == 0) ? 'font-weight:600;' :''}">${(row.created_date).slice(11)}</p>`;
                            }
                        },
                        {
                            data: null,
                            render: function(data, type, row) {
                                return `<div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="customSwitch${row.id}" onclick="change_status(${row.id})" id="customSwitch${row.id}">
                                        <label title="change status to tellicalling" style="cursor:pointer" class="custom-control-label" for="customSwitch${row.id}"></label>
                                    </div>`;
                            }
                        },
                        {
                            data: null,
                            render: function(data, type, row) {
                                return `<div class="d-flex justify-content-around    p-1">
                                            <a href="lead/edit?id=${row.id}" title="edit" class="btn btn-warning btn-xs mr5">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <a href="#" title="Delete" onclick="deleteLead(${row.id})" class="btn btn-danger btn-xs">
                                                <i class="fa fa-trash text-white"></i>
                                            </a>
                                    </div class="d-flex">`;
                            }
                        }
                    ]
                    createDataTable("example1", response, cols);
                } else {
                    createDataTable("example1", '', '');
                }
            })
            .catch(function(err) {
                // console.log(err);
            });
    };

    getData();

    var timer = null;

    function startSetInterval() {
        timer = setInterval(function() {
            $('#loader').css('display', 'none');
            getData();
            console.log('kk');
        }, 5000);
    }

    $('body').mousemove(function() {
        clearInterval(timer);
    });

    $('input.form-control.form-control-sm').change(() => {

    });

    $('.buttons-pdf, .buttons-csv').css('height', '33px');


    let change_status = (id) => {
        let postData = {
            'id': id
        }
        ajaxCallData(PANELURL + 'lead/changeStatus', postData, 'POST')
            .then(function(resp) {
                if (resp.success == 1) {
                    getData();
                    notifyAlert(resp.message, 'success');
                } else {
                    notifyAlert('You are not authorized!', 'danger');
                }
            })
            .catch(function(err) {});
    };

    let deleteLead = (id) => {
        let postData = {
            'id': id,
        }
        ajaxCallData(PANELURL + 'lead/delete', postData, 'POST')
            .then(function(resp) {
                if (resp.success == 1) {
                    getData();
                    notifyAlert('Deleted successfully!', 'success');
                } else {
                    notifyAlert('You are not authorized!', 'danger');
                }
            })
            .catch(function(err) {});
    };
</script>
@endsection