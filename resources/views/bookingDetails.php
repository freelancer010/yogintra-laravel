<?php
$this->load->view('includes/header');
?>
<style>
    .list-group-item {
        border: 0px solid rgba(0, 0, 0, .125);
        border-bottom: 1px solid rgba(0, 0, 0, .125);
    }
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6 d-flex">
                    <a href="<?php echo base_url(); ?>event" class="btn btn-secondary btn-sm"><i
                            class="fas fa-backward"></i></a>&nbsp;
                    <h1>Booking Details</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">User Booking Details</li>
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
                            <p class="text-muted text-center my-12">Booking Details</p><br/><br/>
							<div class="overlay hidden">
                            <i class="fas fa-2x fa-sync-alt"></i>
                        </div>
                            <ul class="list-groups list-group-unbordered mb-3 row align-items-start"></ul>
                        </div>
                    </div>
                </div>
            </div>
    </section>
</div>
<?php
$this->load->view('includes/footer');
?>
<script>
    let param = "<?= $_GET['id'] ?>";
    let getData = () => {
        var apiUrl = PANELURL + 'event/view';
        ajaxCallData(apiUrl, { 'bookingId': param }, 'POST')
            .then(function (result) {
                resp = JSON.parse(result);
                resp = resp['data'];
                resppaymentDetails= resp['paymentDetails'];

                $('.list-groups').append(`
                                <li class="list-group-item col-lg-6 col-sm-12">
                                    <b>Client Name&nbsp;:</b><span class="mx-2">${resp.client_name}</span>
                                    <a class="float-right"></a>
                                </li>
                                <li class="list-group-item col-lg-6 col-sm-12">
                                    <b>Number&nbsp;:</b><span class="mx-2">${resp.client_number}</span>
                                    <a class="float-right">
                                    </a>
                                </li>
                                <li class="list-group-item col-lg-6 col-sm-12">
                                    <b>Email&nbsp;:</b><span class="mx-2">${resp.email}</span>
                                    <a class="float-right">
                                    </a>
                                </li>
                                <li class="list-group-item col-lg-6 col-sm-12">
                                    <b>Country&nbsp;:</b><span class="mx-2">${resp.country}</span>
                                    <a class="float-right">
                                    </a>
                                </li>
                                <li class="list-group-item col-lg-6 col-sm-12">
                                    <b>State&nbsp;:</b><span class="mx-2">${resp.state}</span>
                                    <a class="float-right">
                                    </a>
                                </li>
                                <li class="list-group-item col-lg-6 col-sm-12">
                                    <b>City&nbsp;:</b><span class="mx-2">${resp.city}</span>
                                    <a class="float-right">
                                    </a>
                                </li>
                                <li class="list-group-item col-lg-6 col-sm-12">
                                    <b>Event Type&nbsp;:</b><span class="mx-2">${resp.class_type}</span>
                                    <a class="float-right">
                                    </a>
                                </li>
                                <li class="list-group-item col-lg-6 col-sm-12">
                                    <b>Event Name&nbsp;:</b><span class="mx-2">${resp.event_name}</span>
                                    <a class="float-right">
                                    </a>
                                </li>
                                <li class="list-group-item col-lg-6 col-sm-12">
                                    <b>Event Date&nbsp;:</b><span class="mx-2">${resp.created_date}</span>
                                    <a class="float-right">
                                    </a>
                                </li>
                                <li class="list-group-item col-lg-6 col-sm-12">
                                    <b>Package&nbsp;:</b><span class="mx-2">${resp.package}</span>
                                    <a class="float-right">
                                    </a>
                                </li>
                                <li class="list-group-item col-lg-6 col-sm-12">
                                    <b>Payment Type&nbsp;:</b><span class="mx-2">${resp.payment_type}</span>
                                    <a class="float-right">
                                    </a>
                                </li>
                                <li class="list-group-item col-lg-6 col-sm-12">
                                    <b>Total Amount Paid&nbsp;:</b><span class="mx-2">${resp.totalPayAmount}</span>
                                    <a class="float-right">
                                    </a>
                                </li>
                                
                                
                                ${
                                    resp.payment_type == 'Partition Payment' ?
                                    `<li id="oldinput" class="list-group-item col-lg-12 col-sm-12 text-center  mt-5 mb-5 bg-info p-3">
                                        <b style="border-bottom:1px solid #ddd">Partial Payment History</b>
                                    </li>`: 
                                    `<li class="list-group-item col-lg-6 col-sm-12">
                                        <b>Full Payment Date&nbsp;:</b><span class="mx-2">${(resp.totalPayDate).slice(0,10)}&nbsp; at &nbsp;${(resp.totalPayDate).slice(11)}</span>
                                        <a class="float-right"></a>
                                    </li>`
                                }
                                <a href="${PANELURL}event/editEvents?id=${resp.id}" class="btn btn-primary btn-block"><b>Edit Event</b></a>


                                `);

                                $.each(resppaymentDetails, function(){
                                newRowAdd =
                                    `<div id="row" class="row mt-2">
                                        <div class="input-group col-6">
                                            <p class="amount">Amount:&nbsp;&nbsp;${this.amount}</p>
                                        </div>
                                        <div class="col-6">
                                            <p lass="amountDate">Date:&nbsp;&nbsp;${(resp.created_date).slice(0,10)}&nbsp; at &nbsp;${(resp.created_date).slice(11)}</p>
                                        </div>
                                    </div>`;
                                    $('#oldinput').append(newRowAdd);
                                });
            })
            .catch(function (err) {
                console.log(err);
            });
    };

    getData();
</script>
