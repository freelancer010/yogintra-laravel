@extends('layouts.layout')
@section('content')
<style>
    .list-group-item {
        border: 0px solid rgba(0, 0, 0, .125);
        border-bottom: 1px solid rgba(0, 0, 0, .125);
    }

    .hide {
        display: none;
    }

    .view {
        cursor: pointer;
    }

    .show-now {
        display: block;
    }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6 d-flex">
                    <button class="btn btn-secondary btn-sm" id="back-btn"><i class="fas fa-backward"></i></button>&nbsp;
                    <h1>Profile</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Trainer Profile</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">

                    <!-- Profile Image -->
                    <div class="card card-primary card-outline">
                        <div class="card-body box-profile">
                            <p class="text-muted text-center">Profile Details</p>
                            <div class="text-center">
                                <img style="height:200px; width:200px" id="profile" class="profile-user-img img-fluid img-circle"
                                    src="{{ asset('assets/dist/img/default-profile.png') }}" alt="User profile picture">
                            </div>
                            <br>
                            <ul class="list-groups list-group-unbordered mb-3 row align-items-start"></ul>
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
    const param = "{{ request('id') }}";

    let viewNum = () => {
        $('#show-number').toggleClass('hide')
    };

    let viewEmail = () => {
        $('#show-email').toggleClass('hide')
    };

    let changeReadStatus = () => {
        var apiUrl = PANELURL + 'trainers/changeReadStatus';
        ajaxCallData(apiUrl, {
                'id': param
            }, 'POST')
            .then(function(result) {})
            .catch(function(err) {
                console.log(err);
            });
    }

    let getData = () => {
        let postData = {
            'id': param
        }
        var apiUrl = PANELURL + 'trainers/profile';
        ajaxCallData(apiUrl, postData, 'POST')
            .then(function(result) {
                resp = result.data;
                resp.profile_image ? $('#profile').attr('src', PANELURL + resp.profile_image) : ' ';

                $('.list-groups').append(`
                            <li class="list-group-item col-lg-6">
                                    <b>Name&nbsp;:</b><span class="mx-2">${resp.name}</span>
                                    <a class="float-right">
                                        <div class="custom-control custom-switch">
                                        </div>
                                    </a>
                                </li>                                
                                <li class="list-group-item col-lg-6">
                                    <b>Number&nbsp;:</b><span class="mx-2"><i onClick="viewNum()" class="fas view fa-eye"></i><span id="show-number" class="hide ml-5 ">${resp.number}</span></span>
                                    <a class="float-right">
                                        <div class="custom-control custom-switch">
                                        </div>
                                    </a>
                                </li>
                                <li class="list-group-item col-lg-6">
                                    <b>Email&nbsp;:</b><span class="mx-2"><i onClick="viewEmail()" class="fas view fa-eye"></i><span id="show-email" class="hide  ml-5">${resp.email}</span></span>
                                    <a class="float-right">
                                        <div class="custom-control custom-switch">
                                        </div>
                                    </a>
                                </li>
                                <li class="list-group-item col-lg-6">
                                    <b>DOB&nbsp;:</b><span class="mx-2">${resp.dob}</span>
                                    <a class="float-right">
                                        <div class="custom-control custom-switch">
                                        </div>
                                    </a>
                                </li>
                                <li class="list-group-item col-lg-6">
                                    <b>Gender&nbsp;:</b><span class="mx-2">${resp.gender}</span>
                                    <a class="float-right">
                                        <div class="custom-control custom-switch">
                                        </div>
                                    </a>
                                </li>
                                <li class="list-group-item col-lg-6">
                                    <b>Country&nbsp;:</b><span class="mx-2">${resp.country}</span>
                                    <a class="float-right">
                                        <div class="custom-control custom-switch">
                                        </div>
                                    </a>
                                </li>
                                <li class="list-group-item col-lg-6">
                                    <b>State&nbsp;:</b><span class="mx-2">${resp.state}</span>
                                    <a class="float-right">
                                        <div class="custom-control custom-switch">
                                        </div>
                                    </a>
                                </li>
                                <li class="list-group-item col-lg-6">
                                    <b>City&nbsp;:</b><span class="mx-2">${resp.city}</span>
                                    <a class="float-right">
                                        <div class="custom-control custom-switch">
                                        </div>
                                    </a>
                                </li>
                                <li class="list-group-item col-lg-6">
                                    <b>Address&nbsp;:</b><span class="mx-2">${resp.address}</span>
                                    <a class="float-right">
                                        <div class="custom-control custom-switch">
                                        </div>
                                    </a>
                                </li>
                                <li class="list-group-item col-lg-6">
                                    <b>Certification&nbsp;:</b><span class="mx-2">${resp.certification}</span>
                                    <a class="float-right">
                                        <div class="custom-control custom-switch">
                                        </div>
                                    </a>
                                </li>
                                <li class="list-group-item col-lg-6">
                                    <b>Education&nbsp;:</b><span class="mx-2">${resp.Education}</span>
                                    <a class="float-right">
                                        <div class="custom-control custom-switch">
                                        </div>
                                    </a>
                                </li>
                                <li class="list-group-item col-lg-6">
                                    <b>Experience&nbsp;:</b><span class="mx-2">${resp.experience}</span>
                                    <a class="float-right">
                                        <div class="custom-control custom-switch">
                                        </div>
                                    </a>
                                </li>
                                <li class="list-group-item col-lg-6">
                                    <b>Other Certificate&nbsp;:</b><span class="mx-2">${resp.Other_Certificate}</span>
                                    <a class="float-right">
                                        <div class="custom-control custom-switch">
                                        </div>
                                    </a>
                                </li>
                                <li class="list-group-item col-lg-6">
                                    <b>Trainer's Message&nbsp;:</b><span class="mx-2">${resp.message}</span>
                                    <a class="float-right">
                                        <div class="custom-control custom-switch">
                                        </div>
                                    </a>
                                </li>
                                <li class="list-group-item col-lg-6">
                                    <b>Trainer's Package&nbsp;:</b><span class="mx-2">${resp.package}</span>
                                    <a class="float-right">
                                        <div class="custom-control custom-switch">
                                        </div>
                                    </a>
                                </li>
                                <li class="list-group-item col-lg-6">
                                    <b>Date Added&nbsp;:</b><span class="mx-2">${resp.created_date}</span>
                                    <a class="float-right">
                                        <div class="custom-control custom-switch">
                                        </div>
                                    </a>
                                </li>                              
                                <li class="list-group-item col-lg-6">
                                    <b>Trainer CV&nbsp;:</b>
                                    <span class="mx-2">${ resp.cv_filecfdb7_file ? `<a target="_blank" href="${PANELURL + resp.cv_filecfdb7_file}"><i class="fas fa-eye"></i></a>`:`<span class="text-danger">Please Upload CV First</span>`}</span>
                                    <a class="float-right">
                                        <div class="custom-control custom-switch">
                                        </div>
                                    </a>
                                </li>
                                <li id="trDoc" class="list-group-item col-lg-6">
                                    <b>Trainer Document&nbsp;:</b>
                                    <span class="mx-2">
                                        ${ resp.doc ? 
                                            `<a target="_blank" href="${PANELURL + resp.doc}"><i class="fas fa-eye"></i></a>`:
                                            `<span class="text-danger">Please Upload Document First</span>`
                                        }
                                    </span>
                                    <a class="float-right">
                                        <div class="custom-control custom-switch">
                                        </div>
                                    </a>
                                </li>
                                <a href="${PANELURL}trainers/edit?id=${resp.id}" class="btn btn-primary btn-block"><b>Edit Profile</b></a>
                                `);
                if (resp.is_trainer == 0) {
                    $('#trDoc').css('display', 'none');
                    $('#profile').css('display', 'none');
                    $('#back-btn').on('click', () => {
                        window.location.replace(PANELURL + '/recruiter')
                    });
                } else {
                    $('#back-btn').on('click', () => {
                        window.location.replace(PANELURL + 'trainers')
                    });
                }
            })
            .catch(function(err) {
                console.log(err);
            });
    };
    changeReadStatus();

    getData();
</script>
@endsection