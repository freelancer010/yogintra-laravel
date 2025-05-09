@extends('layouts.layout')
@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Trainers</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Trainers</li>
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
                                        <button  type="button" class="btn btn-sm btn-success mr-3 " onclick=filter()>
                                            Generate&nbsp;&nbsp;<i class="fas fa-arrow-right"></i>
                                        </button>
                                        <!-- <button type="button" class="btn btn-danger mr-3" onclick=reset()>reset</button> -->
                                    </div>
                                    <div class="d-flex mr-1 align-items-center">
                                        <!-- <label for="fromDate" class="exampleInputEmail1 mr-1 text-muted ">From</label> -->
                                        <input style="height: 32px;" type="date" class="form-control mr-3" id="fromDate" max="<?php echo date('Y-m-d');?>">
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <label for="toDate" class="exampleInputEmail1 mt-1 mr-3 text-muted">To</label>
                                        <input style="height: 32px;" type="date" class="form-control mr-1" id="toDate" max="<?php echo date('Y-m-d');?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th style="width:20px !important; text-align:center !important">ID</th>
                                        <th style="width:230px !important; text-align:center !important">Trainer Name</th>
                                        <th style="width:150px !important; text-align:center !important">Trainer Number</th>
                                        <th style="width:150px !important; text-align:center !important">Country</th>
                                        <th style="width:150px !important; text-align:center !important">State</th>
                                        <th style="width:150px !important; text-align:center !important">City</th>
                                        <th style="width:150px !important; text-align:center !important">Change to Recruit</th>
                                        <th style="width:150px !important; text-align:center !important">is Featured <br/>Trainer ?</th>
                                        <th style="width:150px !important; text-align:center !important">Show/Hide <br/>Trainer</th>
                                        <th style="width:150px !important; text-align:center !important">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
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
        getData(fromDate,toDate);
    }

    let reset = () => {
        let toDate = $("#toDate").val('');
        let fromDate = $("#fromDate").val('');
        getData();
    }

    let getData = (startDate='', endDate='')=>{
        var apiUrl = PANELURL + 'trainers/view';
        ajaxCallData(apiUrl, {
            'trainers': 'trainers',
            startDate: startDate,
            endDate: endDate
        }, 'POST')
        .then(function (resp) {
            if(resp.success == 1){
                response = resp.data;

                let cols = [
                    { data: "id" },
                    { data: null,
                        render: function (data, type, row) {
                            return `<a href="${PANELURL}trainers/profile?id=${row.id}">${row.name}</a>`;
                        }
                    },
                    { data: "number" },
                    { data: "country" },
                    { data: "state" },
                    { data: "city" },
                    { data: null,
                        render: function (data, type, row) {
                            console.log(row)
                            return `<div class="custom-control custom-switch text-center">
                                        <input type="checkbox" class="custom-control-input" onclick="change_status(${row.id},${row.is_trainer})" id="customSwitch${row.id}">
                                        <label style="cursor:pointer" class="custom-control-label" for="customSwitch${row.id}"></label>
                                    </div>`;
                        } 
                    },
                    { data: null,
                        render: function (data, type, row) {
                            return `<div class="custom-control custom-switch text-center">
                                        <input ${row.is_featured_trainer==1 ? 'checked' : ''} type="checkbox" class="custom-control-input" onclick="is_featured_trainer(${row.id},${row.is_featured_trainer})" id="customSwitchtFeatured${row.id}">
                                        <label style="cursor:pointer" class="custom-control-label" for="customSwitchtFeatured${row.id}"></label>
                                    </div>`;
                        } 
                    },
                    //show hide trainer
                    { data: null,
                        render: function (data, type, row) {

                            return `<div class="custom-control custom-switch text-center">
                                        <input ${row.show_trainer==1 ? 'checked' : ''} type="checkbox" class="custom-control-input" onclick="show_trainer(${row.id},${row.show_trainer})" id="customSwitcht${row.id}">
                                        <label style="cursor:pointer" class="custom-control-label" for="customSwitcht${row.id}"></label>
                                    </div>`;
                        } 
                    },
                    {data: null,
                        render: function (data, type, row) {
                            return `<div class="d-flex justify-content-between">
                                        <a href="${PANELURL}trainers/edit?id=${row.id}" class="btn btn-warning btn-xs mr5">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <a href="#" title="Delete" onclick="deleteRecruit(${row.id})" class="btn btn-danger btn-xs">
                                        <i class="fa fa-trash"></i></a>
                                    </div class="d-flex">`;
                        }
                    }
                    
                ]
                createDataTable("example1", response, cols);
            }else{
                createDataTable("example1",'','');
            }
        })
        .catch(function (err) {
            console.log(err);
        });
    };
    
    getData();

    let change_status = (id,status) => {
        let postData = {
            'id':id,
            'status':status
        }
        ajaxCallData(PANELURL + 'trainers/changeStatus',postData, 'POST')
            .then(function (result) {
                jsonCheck  = isJSON(result);
                if(jsonCheck==true){
                    resp = JSON.parse(result);
                    if(resp.success==1){
                        getData();
                        notifyAlert(resp.message,'success');
                    }else{
                        notifyAlert('You are not authorized!','danger');
                    }
                }else{
                    notifyAlert('You are not authorized!','danger');
                }
            })
            .catch(function (err) {
                console.log(err);
            });
    };

    let deleteRecruit = (id) => {
        let postData = {
            'id': id,
        }
        ajaxCallData(PANELURL + 'trainers/delete', postData, 'POST')
            .then(function (result) {
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
            .catch(function (err) {
                // console.log(err);
            });
    };

    let show_trainer = (id,status) => {
        let postData = {
            'id':id,
            'status':status
        }
        ajaxCallData(PANELURL + 'trainers/show_trainer',postData, 'POST')
            .then(function (result) {
                jsonCheck  = isJSON(result);
                if(jsonCheck==true){
                    resp = JSON.parse(result);
                    if(resp.success==1){
                        getData();
                        notifyAlert(resp.message,'success');
                    }else{
                        notifyAlert('You are not authorized!','danger');
                    }
                }else{
                    notifyAlert('You are not authorized to do this!','danger');
                }
            })
            .catch(function (err) {
                console.log(err);
            });
    };
    let is_featured_trainer = (id,status) => {
        let postData = {
            'id':id,
            'status':status
        }
        console.log(postData);
        ajaxCallData(PANELURL + 'trainers/is_featured_trainer',postData, 'POST')
            .then(function (result) {
                console.log(result);
                jsonCheck  = isJSON(result);
                if(jsonCheck==true){
                    resp = JSON.parse(result);
                    if(resp.success==1){
                        getData();
                        notifyAlert(resp.message,'success');
                    }else{
                        notifyAlert('You are not authorized!','danger');
                    }
                }else{
                    notifyAlert('You are not authorized to do this!','danger');
                }
            })
            .catch(function (err) {
                console.log(err);
            });
    };
</script>
@endsection

