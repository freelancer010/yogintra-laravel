@extends('layouts.layout')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Yoga Center</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Yoga Center</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header yogintra align-items-center d-flex justify-content-between">
                            <!-- <h3 class="card-title">All EVENTS here</h3> -->
                            <a href="yoga-bookings/add" class="btn btn-sm btn-primary"><i
                                    class="fas fa-plus"></i>&nbsp;&nbsp;Add Yoga Center</a>
                            <a href="#" onclick="filterDue()" class="btn btn-sm btn-secondary ml-auto">&nbsp;&nbsp;Due
                                Customers</a>
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
                                        <input style="height: 32px;" type="date" class="form-control mr-3" id="fromDate"
                                            max="<?php echo date('Y-m-d'); ?>">
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <label for="toDate" class="exampleInputEmail1 mt-1 mr-3 text-muted">To</label>
                                        <input style="height: 32px;" type="date" class="form-control mr-1" id="toDate"
                                            max="<?php echo date('Y-m-d', strtotime("tomorrow")); ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th style="width:5% !important">ID</th>
                                        <th style="width:8% !important">Client Name</th>
                                        <th style="width:8% !important">Client Number</th>
                                        <!-- <th style="width:10% !important">Yoga Center</th> -->
                                        <th style="width:10% !important">Start Date</th>
                                        <th style="width:10% !important">End Date</th>
                                        <th style="width:5% !important">Full Pay</th>
                                        <th style="width:6% !important">Action</th>
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

    let filterDue = () => {
        getData([], [], 'totalPayAmount');
    }
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

    let getData = (startDate = '', endDate = '', due_type = '') => {
        var apiUrl = PANELURL + 'yoga-bookings/view';
        ajaxCallData(apiUrl, {
                'yoga': 'getData',
                startDate: startDate,
                endDate: endDate,
                due_type: due_type
            }, 'POST')
            .then(function(resp) {
                if (resp.success == 1) {
                    response = resp.data;
                    response['trainer'] = resp.trainer;

                    let cols = [{
                            data: "id"
                        },
                        {
                            data: null,
                            render: function(data, type, row) {
                                return `<a href="${PANELURL}yoga-bookings/profile/${row.id}">${row.client_name}</a>`;
                            }
                        },
                        {
                            data: "client_number"
                        },
                        // { data: "event_name"},
                        {
                            data: "s_date"
                        },
                        {
                            data: "e_date"
                        },
                        {
                            data: "totalPayAmount"
                        },
                        {
                            data: null,
                            render: function(data, type, row) {

                                let buttons = '';

                                @if(session('admin_role_id') == 1)
                                buttons += `<button title="Delete" onclick="deletecustomer(${row.id})" class="btn btn-danger btn-xs">
                                                <i class="fa fa-trash"></i>
                                            </button>`;

                                buttons += `<button title="change status to leads" onclick="change_back_toLeads(${row.lead_transfer_id}, ${row.id})" class="btn btn-success btn-xs mr5">
                                                <i class="fa fa-reply mr5"></i>
                                            </button>`;
                                @endif

                                buttons += `<a href="{{ url('yoga-bookings/edit') }}?id=${row.id}" title="Edit" class="btn btn-warning btn-xs mr-1">
                                            <i class="fa fa-edit"></i>
                                        </a>`;
                                buttons += `<a target="_blank" href="{{ url('invoice/yoga') }}?id=${row.id}" title="Download Invoice" class="btn btn-secondary btn-xs">
                                            <i class="fa fa-download"></i>
                                        </a>
                                        ${row.renew_skip == 1 ? `
                                            <button title="Move to renew" onclick="movetorenew(${row.id})" class="btn btn-warning btn-xs mr5">
                                                <i class="fas fa-redo-alt"></i>
                                            </button>`: ''}
                                        </div>`;

                                return `<div class="d-flex justify-content-between px-1">${buttons}</div>`;
                            }
                        }
                    ];
                    createDataTable("example1", response, cols);
                } else {
                    createDataTable("example1", '', '');
                }
                $('.buttons-pdf, .buttons-csv').css('height', '33px');
            })
            .catch(function(err) {
                console.log(err);
            });
    };

    getData();

    let change_back_toLeads = (lead_id, id) => {
        if (lead_id) {
            let postData = {
                'lead_id': lead_id,
                'id': id
            }
            ajaxCallData(PANELURL + 'customer/changeStatusToTelecallingFromYogaCenter', postData, 'POST')
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
        } else {
            notifyAlert('This data cannot be Back!', 'danger');
        }
    };

    let deletecustomer = (id) => {
        let postData = {
            'id': id,
        }
        ajaxCallData(PANELURL + 'yoga-bookings/delete', postData, 'POST')
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

    let movetorenew = (id) => {
        let postData = {
            'id': id,
        }
        ajaxCallData(PANELURL + 'renewal/moveToRenew?type=yoga', postData, 'POST')
            .then(function(result) {
                jsonCheck = isJSON(result);
                if (jsonCheck == true) {
                    resp = JSON.parse(result);
                    if (resp.success == 1) {
                        getData();
                        notifyAlert('Data moved to Renewal Successfully!', 'success');
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