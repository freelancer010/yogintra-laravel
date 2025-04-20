@extends('layouts.layout')
@section('content')

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>All Data</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">All Data</li>
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
                            <div class="row align-items-center ml-auto" style="margin-bottom:-2px">
                                <div class="filter d-flex justify-content-center align-items-center">
                                    <div class="d-flex mr-1 align-items-center">
                                        <button type="button" class="btn btn-sm btn-success mr-3" onclick="filter()">
                                            Generate&nbsp;&nbsp;<i class="fas fa-arrow-right"></i>
                                        </button>
                                    </div>
                                    <div class="d-flex mr-1 align-items-center">
                                        <input style="height: 32px;" type="date" class="form-control mr-3" id="fromDate"
                                            max="{{ date('Y-m-d') }}">
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <label for="toDate" class="exampleInputEmail1 mt-1 mr-3 text-muted">To</label>
                                        <input style="height: 32px;" type="date" class="form-control mr-1" id="toDate"
                                            max="{{ date('Y-m-d') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Client Name</th>
                                        <th>Client Number</th>
                                        <th>Country</th>
                                        <th>State</th>
                                        <th>City</th>
                                        <th style="width:200px !important">Type Of Class</th>
                                        <th style="width:200px !important">Date</th>
                                        <th>Lead Stage</th>
                                        <th>Action</th>
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

    const filter = () => {
        const toDate = $("#toDate").val();
        const fromDate = $("#fromDate").val();
        getData(fromDate, toDate);
    };

    const reset = () => {
        $("#toDate").val('');
        $("#fromDate").val('');
        getData();
    };

    const getData = (startDate = '', endDate = '') => {
        const apiUrl = PANELURL + 'allData';
        ajaxCallData(apiUrl, {
                startDate,
                endDate
            }, 'POST')
            .then(function(resp) {
                if (resp.success == 1) {
                    const response = resp.data;
                    const cols = [{
                            data: "id"
                        },
                        {
                            data: null,
                            render: function(data, type, row) {
                                return `<a href="${PANELURL}profile?id=${row.id}&source=alldata">${row.name}</a>`;
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
                                return `${(row.status == 1) ? '<p class="mx-3 my-auto bg-secondary p-1 text-center" style="border-radius:12px">Lead</p>' :
                                        (row.status == 2) ? '<p class="mx-3 my-auto bg-warning p-1 text-center" style="border-radius:12px">Telecalling</p>' :
                                        (row.status == 3) ? '<p class="mx-3 my-auto bg-success p-1 text-center" style="border-radius:12px">Customer</p>' :
                                        (row.status == 4) ? '<p class="mx-3 my-auto bg-danger p-1 text-center" style="border-radius:12px">Rejected</p>' :
                                        (row.status == 5) ? '<p class="mx-3 my-auto bg-info p-1 text-center" style="border-radius:12px">Renewal</p>' : ''
                                }`;
                            }
                        },
                        {
                            data: null,
                            render: function(data, type, row) {
                                return `<div class="d-flex justify-content-between px-3">
                                        <a href="profile/edit?id=${row.id}&source=alldata" title="edit" class="btn btn-warning btn-xs mr5">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <button title="delete this row" onclick="deleteTelecalling(${row.id})" class="btn btn-danger btn-xs">
                                            <i class="fa fa-trash"></i>
                                        </button>
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

    const deleteTelecalling = (id) => {
        const postData = {
            id
        };
        ajaxCallData(PANELURL + 'telecalling/deleteData', postData, 'POST')
            .then(function(result) {
                const jsonCheck = isJSON(result);
                if (jsonCheck) {
                    const resp = JSON.parse(result);
                    notifyAlert(resp.success == 1 ? 'Deleted successfully!' : 'You are not authorized!', resp.success == 1 ? 'success' : 'danger');
                    getData();
                } else {
                    notifyAlert('You are not authorized!', 'danger');
                }
            })
            .catch(console.log);
    };
</script>
@endsection