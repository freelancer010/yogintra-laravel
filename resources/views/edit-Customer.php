<?php
$this->load->view('includes/header');
?>
<style>
    .avatar-upload {
        position: relative;
        max-width: 205px;
        margin: 50px auto;
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

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6 d-flex">
                    <button class="btn btn-secondary btn-sm" onclick="history.back()"><i class="fas fa-backward"></i></button>&nbsp;
                    <h1>Edit Profile</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Edit Profile</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="col-md-12">

                <!-- Profile Image -->
                <div class="card card-primary card-outline">
                    <div class="card-body">
                        <form id="addLeads">
                            <div class="card-body">
                                <div class="row align-items-start">
                                    <div class="col-lg-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="clientName">Client Name</label>
                                            <input required type="text" class="form-control" id="clientName" name="name"
                                                placeholder="Enter Client Name">
                                        </div>
                                        <div class="form-group">
                                            <label for="clientNumber">Client Number</label>
                                            <input required type="number" class="form-control" id="clientNumber"
                                                name="number" placeholder="Enter Client Number">
                                        </div>
                                        <div class="form-group">
                                            <label>Country</label>
                                            <input required type="text" class="form-control" id="country"
                                                name="country" placeholder="Enter Country name">
                                        </div>
                                        <div class="form-group">
                                            <label>State</label>
                                            <input required type="text" class="form-control" id="state"
                                                name="state" placeholder="Enter state name">
                                        </div>
                                        <div class="form-group">
                                            <label>City</label>
                                            <input required type="text" class="form-control" id="city"
                                                name="city" placeholder="Enter city name">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="clientEmail">Email address</label>
                                            <input required type="email" class="form-control" id="clientEmail"
                                                name="email" placeholder="Enter email">
                                        </div>
                                        <div class="form-group">
                                            <label for="clientSource">Lead Source</label>
                                            <input required type="text" class="form-control" id="clientSource"
                                                name="lead-source" placeholder="Enter lead source">
                                        </div>
                                        <div class="form-group">
                                            <label>Type of Class</label>
                                            <select id="class_type" name="class" class="form-control">
                                                <option selected>Select Your Class type</option>
                                                <option value="Home Visit Yoga">Home Visit Yoga</option>
                                                <option value="Private Online Yoga">Private Online Yoga</option>
                                                <option value="Group Online Yoga">Group Online Yoga</option>
                                                <option value="TTC">TTC
                                                </option>
                                                <option value="Retreat / Workshop Booking">Retreat / Workshop Booking
                                                </option>
                                                <option value="Corporate Yoga">Corporate Yoga</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="clientCallFrom">Call From</label>
                                            <input required type="time" class="form-control" id="clientCallFrom"
                                                name="call-from" placeholder="Enter call start time">
                                        </div>
                                        <div class="form-group">
                                            <label for="clientCallTo">Call To</label>
                                            <input required type="time" class="form-control" id="clientCallTo"
                                                name="call-to" placeholder="Enter call end time">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="clientMessage">Client's Message</label>
                                            <input required type="text" class="form-control" id="clientMessage"
                                                name="client-message" placeholder="Enter your message">
                                        </div>
                                        <div class="form-group">
                                            <label for="attendeeName">Attendee Name</label>
                                            <input required type="text" class="form-control" id="attendeeName"
                                                name="attendeeName" placeholder="Enter attendee name">
                                        </div>
                                        <div class="form-group">
                                            <label for="date">Date</label>
                                            <input required type="date" class="form-control" id="date" name="date"
                                                placeholder="Pickup date">
                                        </div>
                                        <div class="form-group">
                                            <label for="package">Package</label>
                                            <input required type="text" class="form-control" id="package" name="package"
                                                placeholder="Enter your package">
                                        </div>
                                        <div class="form-group">
                                            <label for="quotation">Quotation</label>
                                            <input required type="text" class="form-control" id="quotation"
                                                name="quote" placeholder="Enter your message">
                                        </div>
                                        <div class="form-group">
                                            <label for="demopay">Demo</label>
                                            <input required type="text" class="form-control" id="demopay"
                                                name="demoPay" placeholder="Enter Trial Pay">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-sm-12">
                                        <div class="form-group" id="attemp1Holder">
                                            <p class="mb-1">Attempt 1</p>
                                            <div class="btn-group  justify-content-between"  data-toggle="buttons">
                                                <label class="btn btn-danger">
                                                    <input type="radio" name="attempt1" id="option_a1" autocomplete="off" value="0" checked="">
                                                    Pending
                                                </label>
                                                <label class="btn btn-success active">
                                                    <input type="radio" name="attempt1" id="option_a2" value="1" autocomplete="off">
                                                    Done
                                                </label>
                                            </div>
                                            <input type="text" class="form-control mb-2" id="remarks1" name="remarks1" placeholder="Enter user remarks">
                                            <input type="text" class="form-control atemptDate" id="atemptDate1" name="atemptDate1" value="">
                                        </div>

                                        <div class="form-group" id="attemp2Holder">
                                            <p class="mb-1">Attempt 2</p>
                                            <div class="btn-group  justify-content-between"  data-toggle="buttons">
                                                <label class="btn btn-danger">
                                                    <input type="radio" name="attempt2" id="option_a3" autocomplete="off" checked value="0">
                                                    Pending
                                                </label>
                                                <label class="btn btn-success active">
                                                    <input type="radio" name="attempt2" id="option_a4" value="1" autocomplete="off">
                                                    Done
                                                </label>
                                            </div>
                                            <input type="text" class="form-control mb-2" id="remarks2" name="remarks2" placeholder="Enter user remarks">
                                            <input type="text" class="form-control atemptDate" id="atemptDate2" name="atemptDate2" value="">
                                        </div>

                                        <div class="form-group" id="attemp3Holder">
                                            <p class="mb-1">Attempt 3</p>
                                            <div class="btn-group  justify-content-between"  data-toggle="buttons">
                                                <label class="btn btn-danger">
                                                    <input type="radio" name="attempt3" id="option_a5" autocomplete="off" value="0" checked>
                                                    Pending
                                                </label>
                                                <label class="btn btn-success active">
                                                    <input type="radio" name="attempt3" id="option_a6" value="1" autocomplete="off">
                                                    Done
                                                </label>
                                            </div>
                                            <input type="text" class="form-control mb-2" id="remarks3" name="remarks3" placeholder="Enter user remarks">
                                            <input type="text" class="form-control atemptDate" id="atemptDate3" name="atemptDate3" value="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="modal-footer editProfileContainer justify-content-between">
                        <button type="button" class="btn btn-primary w-100" onclick="addLead()">Save</button>
                    </div>
                    </form>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->

            <!-- About Me Box -->
        </div>
        <!-- /.col -->
        <!-- /.row -->
</div><!-- /.container-fluid -->
</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php
$this->load->view('includes/footer');
?>
<script>
    let url = window.location.pathname;
    let param = url.split('/').pop();

    let editLead = (id) => {
        let postData = {
            'id': id,
        }
        ajaxCallData(PANELURL + 'lead/getLeadById', postData, 'POST')
            .then(function (result) {
                resp = JSON.parse(result);
                if (resp.success == 1) {
                    response = resp.data;
                    $('#clientName').val(response.name);
                    $('#clientNumber').val(response.number);
                    $('#country').val(response.country);
                    $('#state').val(response.state);
                    $('#city').val(response.city);
                    $('#clientEmail').val(response.email);
                    $('#clientSource').val(response.source);
                    $('#class_type').val(response.class_type);
                    $('#clientCallFrom').val(response.call_from);
                    $('#clientCallTo').val(response.call_to);
                    $('#clientMessage').val(response.message);
                    $('#attendeeName').val(response.attendeeName);
                    $('#date').val(response.created_date);
                    $('#package').val(response.package);
                    $('#quotation').val(response.quotation);
                    $('#demopay').val(response.dempay);
                    if (response.attempt1 == 1) {
                        $('#option_a2').attr('checked', true);
                    } else if(response.attempt2 == 1){
                        $('#option_a4').attr('checked', true);
                    }else if(response.attempt3 == 1){
                        $('#option_a6').attr('checked', true);
                    }
                    $('#remarks1').val(response.attempt1Remarks);
                    $('#remarks2').val(response.attempt2Remarks);
                    $('#remarks3').val(response.attempt3Remarks);
                } else {

                }
            })
            .catch(function (err) {
                console.log(err);
            });
    };
    editLead(param);


    let addLead = () => {
        console.log($("#addLeads").serializeArray());
        ajaxCallData(PANELURL + 'lead/updatedata', $("#addLeads").serialize(), 'POST')
            .then(function (result) {
                response = JSON.parse(result);
                if (response.success == 1) {
                    $('#addLeads')[0].reset();
                    notifyAlert('Your profile data edited successfully', 'success');
                    setTimeout(() => {
                        window.location.href = PANELURL+"profile?id="+param;
                    }, 1000);
                }
            })
            .catch(function (err) {
                console.log(err);
            });
    } 
    // function clockTick() {
    //     var currentTime = new Date(),
    //         month = currentTime.getMonth() + 1,
    //         day = currentTime.getDate(),
    //         year = currentTime.getFullYear(),
    //         hours = currentTime.getHours(),
    //         minutes = currentTime.getMinutes(),
    //         seconds = currentTime.getSeconds(),
    //         text = (month + "/" + day + "/" + year + ' || ' + hours + ':' + minutes + ':' + seconds);
        $('.atemptDate').val("<?php echo date('m:d:Y') . ' || '. date('h:m:s')?>");
    // }
    // setInterval(clockTick, 1000);

    
    
    
</script>