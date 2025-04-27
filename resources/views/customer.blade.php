@extends('layouts.layout')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Customers</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="dashboard">Home</a></li>
                        <li class="breadcrumb-item active">Customers</li>
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
                            <a href="addCustomer?id=3" class="btn btn-sm btn-primary"><i
                                    class="fas fa-plus"></i>&nbsp;&nbsp;Add
                                Customers</a>&nbsp; &nbsp;
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
                                            max="<?php echo date('Y-m-d'); ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <select id="class_type" name="class" class="form-control editInputBox col-lg-4 m-auto"
                                style="height:30px;margin-bottom:0px !important;display: flex;align-items: center;justify-content: center;padding: 3px 8px;width: 200px;">
                                <option value='' selected>Select Due type</option>
                                <option value="full_payment">Due from Customer</option>
                                <option value="payTotrainer">Due to Trainers</option>
                            </select>
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th style="width:2% !important;">ID</th>
                                        <th style="width:10% !important;">Client Name</th>
                                        <th style="width:6% !important;">Client Number</th>
                                        <th style="width:5% !important;">State</th>
                                        <th style="width:5% !important;">City</th>
                                        <th style="width:5% !important;">Full Payment</th>
                                        <th style="width:5% !important;">Trainer</th>
                                        <th style="width:5% !important;">T Payment</th>
                                        <th style="width:5% !important;">T Date</th>
                                        <th style="width:8% !important;">Action</th>
                                        <!-- <th style="text-align:center">Invoice</th> -->
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

    $('select').on('change', function() {
        getData([], [], this.value);
    });

    let getData = (startDate = '', endDate = '', due_type = '', ) => {
        var apiUrl = PANELURL + 'customer/view';
        ajaxCallData(apiUrl, {
                startDate: startDate,
                endDate: endDate,
                due_type: due_type
            }, 'POST')
            .then(function(resp) {
                if (resp.success == 1) {
                    response = resp.data;
                    response['trainer'] = resp.trainer;
                    let cols = [{
                            data: null,
                            render: function(data, type, row) {
                                return row.id;
                            }
                        },
                        {
                            data: null,
                            render: function(data, type, row) {
                                return `<a href="${PANELURL}customer/profile?id=${row.id}">${row.name}</a>`;
                            }
                        },
                        {
                            data: "number"
                        },
                        {
                            data: "state"
                        },
                        {
                            data: "city"
                        },
                        {
                            data: "full_payment"
                        },
                        {
                            data: "trainerName"
                        },
                        {
                            data: "payTotrainer"
                        },
                        {
                            data: null,
                            render: function(data, type, row) {
                                return (row.trainerPayDate).slice(0, 10);
                            }
                        },
                        {
                            data: null,
                            render: function(data, type, row) {
                                return `<div class="d-flex justify-content-between px-3">
                                            <a href="lead/edit?id=${row.id}" title="edit" class="btn btn-primary btn-xs mr5">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            
                                            <a target ="_blank" href="invoice?id=${row.id}" title="download invoice" class="btn btn-secondary btn-xs mr5 text-white">
                                                <i class="fa fa-download"></i>
                                            </a>
                                            <button href="#" title="delete this row" onclick="deletecustomer(${row.id})" class="btn btn-danger btn-xs">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                            
                                            <button title="change status to tellecalling" onclick="change_back_toLeads(${row.id})" class="btn btn-success btn-xs mr5">
                                                <i class="fa fa-reply mr5"></i>
                                            </button>
                                        ${row.renew_skip == 1 ? `
                                            <button title="Move to renew" onclick="movetorenew(${row.id})" class="btn btn-warning btn-xs mr5">
                                                <i class="fas fa-redo-alt"></i>
                                            </button>`: ''}
                                        </div>`;
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

    let change_back_toLeads = (id) => {
        let postData = {
            'id': id
        }
        ajaxCallData(PANELURL + 'customer/changeStatusToTelecalling', postData, 'POST')
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

    let deletecustomer = (id) => {
        let postData = {
            'id': id,
        }
        ajaxCallData(PANELURL + 'customer/delete', postData, 'POST')
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
        ajaxCallData(PANELURL + 'renewal/moveToRenew?type=lead', postData, 'POST')
            .then(function(resp) {
                if (resp.success == 1) {
                    getData();
                    notifyAlert('Data moved to Renewal Successfully!', 'success');
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