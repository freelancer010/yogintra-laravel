<?php
$this->load->view('includes/header');
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6 d-flex">
                    <button class="btn btn-secondary btn-sm" onclick="history.back()"><i
                            class="fas fa-backward"></i></button>&nbsp;
                    <h1>Add <?php if(basename($_SERVER["REQUEST_URI"]) == 'addCustomer'){echo 'Customer';}else{ echo 'Leads';};?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">Home</li>
                        <li class="breadcrumb-item active">Add <?php if(basename($_SERVER["REQUEST_URI"]) == 'addCustomer'){echo 'Customer';}else{ echo 'Leads';};?></li>
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
                                        <div class="form-group col-lg-6 col-sm-12">
                                            <label for="clientName">Client Name</label>
                                            <input required type="text" class="form-control" id="clientName" name="name"
                                                placeholder="Enter Client Name">
                                        </div>
                                        <div class="form-group col-lg-6 col-sm-12">
                                            <label for="clientNumber">Client Number</label>
                                            <input required type="text" class="form-control" id="clientNumber"
                                                name="number" placeholder="Enter Client Number" pattern= "[0-9]">
                                        </div>
                                        <div class="form-group col-lg-6 col-sm-12">
                                            <label for="clientEmail">Email address</label>
                                            <input required type="email" class="form-control" id="clientEmail"
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
                                            <label>Type of Class</label>
                                            <select id="class_type" name="class" class="form-control editInputBox customEditInputBox">
                                                <option selected>Select Your Class type</option>
                                                <option value="Home Visit Yoga">Home Visit Yoga</option>
                                                <option value="Private Online Yoga">Private Online Yoga</option>
                                                <option value="Group Online Yoga">Group Online Yoga</option>
                                                <option value="Corporate Yoga">Corporate Yoga</option>
                                                <option value="Retreat">Retreat</option>
                                                <option value="Workshop">Workshop</option>
                                                <option value="TTC">TTC</option>
                                                <option value="Yoga Center">Yoga Center</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-6 col-sm-12">
                                            <label for="date">Created Date</label>
                                            <input required type="text" class="form-control" id="date" name="date" readonly value="<?php   date_default_timezone_set('Asia/Kolkata'); echo date("Y-m-d H:i:s", time()); ?>"
                                                placeholder="Pickup date and Time">
                                        </div>


                                        <div class="form-group col-lg-6 col-sm-12">
                                            <label for="clientCallFrom">Call From</label>
                                            <input required type="time" class="form-control" id="clientCallFrom"
                                                name="call-from" placeholder="Enter call start time">
                                        </div>
                                        <div class="form-group col-lg-6 col-sm-12">
                                            <label for="clientCallTo">Call To</label>
                                            <input required type="time" class="form-control" id="clientCallTo"
                                                name="call-to" placeholder="Enter call end time">
                                        </div>
                                        <div class="form-group col-lg-6 col-sm-12">
                                            <label for="clientMessage">Client's Message</label>
                                            <input required type="text" class="form-control" id="clientMessage"
                                                name="client-message" placeholder="Enter your message">
                                        </div>

                                        <div class="form-group col-lg-6 col-sm-12">
                                            <label for="clientSource">Lead Source</label>
                                            <input required type="text" class="form-control" id="clientSource"
                                                name="lead-source" placeholder="Enter lead source">
                                        </div>
                                        <div class="form-group col-lg-6 col-sm-12">
                                            <label for="attendeeName">Attendee Name</label>
                                            <input required type="text" class="form-control" id="attendeeName"
                                                name="attendeeName" placeholder="Enter attendee name" readonly value="<?php echo ($_SESSION['username']); ?>">
                                        </div>
                                        <!-- <div class="form-group col-lg-6 col-sm-12">
                                            <label for="package">Package</label>
                                            <input required type="text" class="form-control" id="package" name="package"
                                                placeholder="Enter your package">
                                        </div>
                                        <div class="form-group col-lg-6 col-sm-12">
                                            <label for="quotation">Quotation</label>
                                            <input required type="text" class="form-control" id="quotation" name="quote"
                                                placeholder="Enter your message">
                                        </div>
                                        <div class="form-group col-lg-6 col-sm-12">
                                            <label for="demopay">Demo</label>
                                            <input required type="text" class="form-control" id="demopay" name="demoPay"
                                                placeholder="Enter Trial Pay">
                                        </div> -->
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
    let addLead = () => {
        ajaxCallData(PANELURL + 'lead/add', $("#addLeads").serialize(), 'POST')
            .then(function (result) {
                response = JSON.parse(result);
                if (response.success == 1) {
                    $('#addLeads')[0].reset();
                    notifyAlert('success!', 'Data Added Successfully');
                    window.location.href = PANELURL+'lead';
                }
                getData();
            })
            .catch(function (err) {
                console.log(err);
            });
    }
</script>