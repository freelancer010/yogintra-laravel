@extends('layouts.layout')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Expense</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">Home</li>
                        <li class="breadcrumb-item active">Expense</li>
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
                            <a href="{{ route('office-expences/add') }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-plus"></i>&nbsp;&nbsp;Add Expenses
                            </a>
                            <div class="row align-items-center" style="margin-bottom:-2px">
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
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th style="width:16% !important">Payee Name</th>
                                        <th style="width:16% !important">Expense Type</th>
                                        <th style="width:16% !important">Expense Amount</th>
                                        <th style="width:15% !important">Date</th>
                                        <th style="font-style:italic;width:25% !important">Note</th>
                                        <th style="width:5% !important">Action</th>
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
        getData('all', fromDate, toDate);
    }

    let reset = () => {
        let toDate = $("#toDate").val('');
        let fromDate = $("#fromDate").val('');
        getData();
    }

    let getData = (class_type = 'all', startDate = '', endDate = '') => {
        var apiUrl = PANELURL + 'office-expences';
        ajaxCallData(apiUrl, {
                'class_type': class_type,
                startDate: startDate,
                endDate: endDate
            }, 'POST')
            .then(function(resp) {
                if (resp.success == 1) {
                    response = resp.data;
                    let cols = [{
                            data: "payee"
                        },
                        {
                            data: "expenseType"
                        },
                        {
                            data: "expenseAmount"
                        },
                        {
                            data: null,
                            render: function(data, type, row) {
                                return `<div style="font-style:italic;"> ${(row.created_date).slice(0,10)}</div>`;
                            }
                        },
                        {
                            data: null,
                            render: function(data, type, row) {
                                return `<div style="font-style:italic;"> ${row.note}</div>`;
                            }
                        },
                        {
                            data: null,
                            render: function(data, type, row) {
                                return `<div class="d-flex justify-content-between p-1">
                                            <a href="office-expences/edit/${row.id}" title="edit" class="btn btn-warning btn-xs mr5">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <a href="#" title="Delete" onclick="deleteExpense(${row.id})" class="btn btn-danger btn-xs">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                    </div class="d-flex">`;
                            }
                        }
                    ]
                    createDataTable("example1", response, cols, 3);
                } else {
                    createDataTable("example1", '', '');
                }
            })
            .catch(function(err) {
                console.log(err);
            });
    };
    getData();
</script>
<script>
    function deleteExpense(e) {

        $.ajax({
            type: "GET",
            url: PANELURL + 'office-expences/delete/' + e,
            success: function(data) {
                if (data == 1) {
                    location.reload();
                    notifyAlert('Data Successfully Deleted', 'success');
                }

            }
        });
    }
</script>
@endsection