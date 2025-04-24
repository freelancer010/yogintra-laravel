@extends('layouts.layout')
@section('content')
<style>
    .avatar-upload {
        position: relative;
        max-width: 205px;
        margin: auto;
    }

    .avatar-upload .avatar-edit {
        position: absolute;
        right: 12px;
        z-index: 1;
        top: 10px;
    }

    .avatar-upload .avatar-edit input {
        display: none;
    }

    .avatar-upload .avatar-edit input+label {
        display: inline-block;
        width: 34px;
        height: 34px;
        margin-bottom: 0;
        border-radius: 100%;
        background: #FFFFFF;
        border: 1px solid transparent;
        box-shadow: 0px 2px 4px 5px rgb(0 0 0 / 12%);
        cursor: pointer;
        font-weight: normal;
        transition: all 0.2s ease-in-out;
        padding: 4px 8px;
    }

    .avatar-upload .avatar-edit input+label:hover {
        background: #f1f1f1;
        border-color: #d6d6d6;
    }

    /* .avatar-upload .avatar-edit input+label:after {
        content: "\f040";
        font-family: 'FontAwesome';
        color: #757575;
        position: absolute;
        top: 10px;
        left: 0;
        right: 0;
        text-align: center;
        margin: auto;
    } */

    .avatar-upload .avatar-preview {
        width: 192px;
        height: 192px;
        position: relative;
        border-radius: 100%;
        border: 6px solid #F8F8F8;
        box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.1);
    }

    .avatar-upload .avatar-preview>div {
        width: 100%;
        height: 100%;
        border-radius: 100%;
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
    }
</style>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6 d-flex">
                    <button class="btn btn-secondary btn-sm" onclick="history.back()"><i class="fas fa-backward"></i></button>&nbsp;
                    <h1>Add Recruit</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Add Recruit</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="col-md-12">
                <div class="card card-primary card-outline">
                    <div class="card-body">
                        <form id="addLeads" enctype="multipart/form-data">
                            <div class="card-body">
                                <div class="container">
                                    <div class="avatar-upload">

                                    </div>
                                </div>

                                <br><br>
                                <div class="row align-items-start">
                                    <div class="form-group col-lg-6 col-sm-12">
                                        <label for="clientName">Trainer Name</label>
                                        <input required type="text" class="form-control editInputBox" id="clientName" name="name"
                                            placeholder="Enter Client Name">
                                    </div>
                                    <div class="form-group col-lg-6 col-sm-12">
                                        <label for="clientNumber">Trainer Number</label>
                                        <input required type="text"
                                            oninput="this.value = this.value.replace(/[^0-9.+]/g, '').replace(/(\..*)\./g, '$1');" class="form-control editInputBox" id="clientNumber"
                                            name="number" placeholder="Enter Client Number">
                                    </div>
                                    <div class="form-group col-lg-6 col-sm-12">
                                        <label for="clientEmail">Email address</label>
                                        <input required type="email" class="form-control editInputBox" id="clientEmail"
                                            name="email" placeholder="Enter email">
                                    </div>


                                    <div class="form-group col-lg-6 col-sm-12">
                                        <label>Country</label>
                                        <select class="form-control countries" id="countryId" name="country">
                                            <option value="selected" selected>Select your Country</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-6 col-sm-12">
                                        <label>States</label>
                                        <select class="form-control states" id="stateId" name="state">
                                            <option value="selected" selected>Select your Country First</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-6 col-sm-12">
                                        <label>City</label>
                                        <select class="form-control cities" id="cityId" name="city">
                                            <option value="selected" selected>Select your state first</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-lg-6 col-sm-12">
                                        <label for="clientEducation">Education</label>
                                        <input required type="text" class="form-control editInputBox" id="clientEducation"
                                            name="education" placeholder="Enter Education">
                                    </div>
                                    <div class="form-group col-lg-6 col-sm-12">
                                        <label for="clienExperience">Experience</label>
                                        <input required type="text" class="form-control editInputBox" id="clienExperience"
                                            name="experience" placeholder="Enter Experience">
                                    </div>
                                    <div class="form-group col-lg-6 col-sm-12">
                                        <label for="clientMessage">Trainer's Message</label>
                                        <input required type="text" class="form-control editInputBox" id="clientMessage"
                                            name="client-message" placeholder="Enter your message">
                                    </div>
                                    <div class="form-group col-lg-6 col-sm-12">
                                        <label for="date">Date Added</label>
                                        <input required type="datetime-local" class="form-control editInputBox" id="date" name="date">
                                    </div>

                                    <div class="form-group col-lg-6 col-sm-12">
                                        <label for="dob">DOB</label>
                                        <input required type="date" class="form-control editInputBox" id="dob" name="dob">
                                    </div>
                                    <div class="form-group col-lg-6 col-sm-12">
                                        <label for="address">Address</label>
                                        <input required type="text" class="form-control editInputBox" id="address" name="address">
                                    </div>
                                    <div class="form-group col-lg-6 col-sm-12">
                                        <label for="gender">Gender</label>
                                        <input required type="text" class="form-control editInputBox" id="gender" name="gender">
                                    </div>
                                    <div class="form-group col-lg-6 col-sm-12">
                                        <label for="certification">Certification</label>
                                        <input required type="text" class="form-control editInputBox" id="certification" name="certification">
                                    </div>
                                    <div class="form-group col-lg-6 col-sm-12">
                                        <label for="Other_Certificate">Other_Certificate</label>
                                        <input required type="text" class="form-control editInputBox" id="Other_Certificate" name="Other_Certificate">
                                    </div>
                                    <div class="form-group col-lg-6 col-sm-12">
                                        <label for="doc">Doc Upload</label>
                                        <input required type="file" class="form-control editInputBox" id="doc" name="doc">
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" id="trainerId" name="trainerId" value="">
                    </div>
                    <div class="modal-footer editProfileContainer justify-content-between">
                        <button type="button" class="btn btn-primary w-100" onclick="addLead()">Save</button>
                    </div>
                    </form>
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

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#imagePreview').css('background-image', 'url(' + e.target.result + ')');
                $('#imagePreview').hide();
                $('#imagePreview').fadeIn(650);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    let addLead = () => {
        $('.editInputBox').prop('disabled', false);
        var data = new FormData();
        var form_data = $('#addLeads').serializeArray();
        $.each(form_data, function(key, input) {
            data.append(input.name, input.value);
        });

        //File data
        var file_data = $('input[name="doc"]')[0].files;
        data.append("profileImage", file_data[0]);

        $.ajax({
            url: PANELURL + 'trainers/add',
            method: "post",
            processData: false,
            contentType: false,
            data: data,
            success: function(response) {
                if (response.success == 1) {
                    $('#addLeads')[0].reset();
                    notifyAlert('Your recruit data added successfully', 'success');
                    window.location.href = PANELURL + "recruiter";
                }
            },
            error: function(e) {
                console.log(err);
            }
        });
    }

    $('.atemptDate').val("<?php echo date('m:d:Y') . ' || ' . date('h:m:s') ?>");
</script>
@endsection