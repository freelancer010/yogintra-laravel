@extends('layouts.layout')
@section('content')
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
                    <button class="btn btn-secondary btn-sm" id="back-btn"><i
                            class="fas fa-backward"></i></button>&nbsp;
                    <h1>Profile</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">User Profile</li>
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
                            <p class="text-muted text-center my-12" style="font-size:28px">Profile Details</p>
                            <br /><br />
                            <ul class="list-groups list-group-unbordered mb-3 row align-items-start"></ul>
                        </div>
                    </div>
                </div>
            </div>
    </section>
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="renewalForm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update Renewal Date</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group col-lg-6 col-sm-12">
                        <label for="clientName">Renew Package End Date</label>
                        <input required type="hidden" id="leadId" name="leadId" value="">
                        <input required type="hidden" id="leadPreviousAmount" name="leadPreviousAmount" value="">
                        <input required type="datetime-local" class="form-control" id="renewalDate" name="renewalDate"
                            placeholder="Enter Renewal Date" value="" readonly>
                    </div>
                    <div class="form-group col-lg-6 col-sm-12">
                        <label for="clientName">Package Renewal Amount</label>
                        <input required type="number" class="form-control" id="renewalAmount" name="renewalAmount"
                            placeholder="Enter Renewal Amount" min="0" value="">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </form>
    </div>
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
    const source = "{{ request('source') }}";

    let changeReadStatus = () => {
        var apiUrl = PANELURL + 'lead/changeReadStatus';
        ajaxCallData(apiUrl, {
                'id': param
            }, 'POST')
            .then(function(result) {})
            .catch(function(err) {
                console.log(err);
            });
    }

    let getData = () => {
        var apiUrl = PANELURL + 'lead/profile';
        ajaxCallData(apiUrl, {
                'id': param
            }, 'POST')
            .then(function(response) {
                resp = response.leads;
                resptrainers = response.trainers;
                resppaymentDetails = response.paymentDetails;
                respRenewDetails = response.renew_details;

                if (source == 'alldata') {
                    $('#back-btn').on('click', () => {
                        redirect('allData');
                    });
                } else {
                    $('#back-btn').on('click', () => {
                        if (resp.status == 1) {
                            redirect('lead');
                        } else if (resp.status == 2) {
                            redirect('telecalling');
                        } else if (resp.status == 3) {
                            redirect('customer');
                        } else if (resp.status == 4) {
                            redirect('rejected');
                        } else if (resp.status == 5) {
                            redirect('renewal');
                        }
                    });
                }
                $('.list-groups').append(`<li class="list-group-item col-lg-6 col-sm-12">
                                    <b>Name&nbsp;:</b><span class="mx-2">${resp.name}</span>
                                    <a class="float-right">
                                    </a>
                                </li>
                                
                                <li class="list-group-item col-lg-6 col-sm-12">
                                    <b>Number&nbsp;:</b><span class="mx-2">${resp.number}</span>
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
                                    <b>Type Of Class&nbsp;:</b><span class="mx-2">${resp.class_type}</span>
                                    <a class="float-right">
                                    </a>
                                </li>
                                <li class="list-group-item col-lg-6 col-sm-12">
                                    <b>Lead created on&nbsp;:</b><span class="mx-2">${(resp.created_date).slice(0, 10)}&nbsp; at &nbsp;${(resp.created_date).slice(11)}</span>
                                    <a class="float-right">
                                    </a>
                                </li>
                                <li class="list-group-item col-lg-6 col-sm-12">
                                    <b>Call From&nbsp;:</b><span class="mx-2">${resp.call_from}</span>
                                    <a class="float-right">
                                    </a>
                                </li>
                                <li class="list-group-item col-lg-6 col-sm-12">
                                    <b>Call To&nbsp;:</b><span class="mx-2">${resp.call_to}</span>
                                    <a class="float-right">
                                    </a>
                                </li>
                                <li class="list-group-item col-lg-6 col-sm-12">
                                    <b>Client's Message&nbsp;:</b><span class="mx-2">${resp.message}</span>
                                    <a class="float-right">
                                    </a>
                                </li>
                                <li class="list-group-item col-lg-6 col-sm-12">
                                    <b>Lead Source&nbsp;:</b><span class="mx-2">${resp.source}</span>
                                    <a class="float-right">
                                    </a>
                                </li>
                                <li class="list-group-item col-lg-6 col-sm-12">
                                    <b>Lead Added By&nbsp;:</b><span class="mx-2">${(resp.attendeeName)}</span>
                                    <a class="float-right">
                                    </a>
                                </li>
                                ${(resp.status == 3 || resp.status == 2) ?
                        `<li class="list-group-item col-lg-6 col-sm-12">
                                    <b>Attendee Name&nbsp;:</b><span class="mx-2">${resp.attendeeName}</span>
                                    <a class="float-right">
                                    </a>
                                </li>
                                <li class="list-group-item col-lg-6 col-sm-12">
                                    <b>Date&nbsp;:</b><span class="mx-2">${resp.created_date}</span>
                                    <a class="float-right">
                                    </a>
                                </li>
                                <li class="list-group-item col-lg-6 col-sm-12">
                                    <b>Package&nbsp;:</b><span class="mx-2">${resp.package}</span>
                                </li>
                                <li class="list-group-item col-lg-6 col-sm-12">
                                    <b>Qotation&nbsp;:</b><span class="mx-2">${resp.quotation}</span>
                                    <a class="float-right">
                                    </a>
                                </li>
                                <li class="list-group-item col-lg-6 col-sm-12">
                                    <b>Trial Pay&nbsp;:</b><span class="mx-2">${resp.dempay}</span>
                                    <a class="float-right">
                                    </a>
                                </li>
                                <li class="list-group-item col-lg-6 col-sm-12">
                                    <b>Trial Date&nbsp;:</b><span class="mx-2">${resp.demDate != '0000-00-00 00:00:00' ? resp.demDate : ''}</span>
                                    <a class="float-right">
                                    </a>
                                </li>
                                ${(resp.status == 2) ?
                            `<li class="list-group-item col-lg-6 col-sm-12">
                                        <b>Attempt Stage:</b>
                                        ${resp.attempt1 == 0 ? '<span style="padding: 3px !important;" class="btn btn-warning">Pending</span>' : resp.attempt1 == 2 ? '<span style="padding: 3px !important;" class="btn btn-danger">Rejected</span>' : ''}
                                    </li>
                                    <li class="list-group-item col-lg-6 col-sm-12">
                                        <b>Attempt 1&nbsp;:</b>
                                        <br>Remarks &nbsp;:<span class="mx-2">${resp.attempt1Remarks}</span>
                                        <br>Date Time &nbsp;:<span class="mx-2">${resp.attempt1Date}</span>
                                    </li>
                                    <li class="list-group-item col-lg-6 col-sm-12">
                                        <b>Attempt 2&nbsp;:</b>
                                        <br>Remarks &nbsp;:<span class="mx-2">${resp.attempt2Remarks}</span>
                                        <br>Date Time &nbsp;:<span class="mx-2">${resp.attempt2Date}</span>
                                    </li>
                                    <li class="list-group-item col-lg-6 col-sm-12">
                                        <b>Attempt 3&nbsp;:</b>
                                        <br>Remarks &nbsp;:<span class="mx-2">${resp.attempt3Remarks}</span>
                                        <br>Date Time &nbsp;:<span class="mx-2">${resp.attempt3Date}</span>
                                    </li>`: ''}` : ''}
                                ${resp.status == 3 ?

                                `<li class="list-group-item col-lg-6 col-sm-12">
                                    <b>Package End Date&nbsp;:</b>
                                    <span class="mx-2">${resp.package_end_date != '0000-00-00 00:00:00' ? resp.package_end_date : ''}</span>
                                </li>
                                <li class="list-group-item col-lg-6 col-sm-12">
                                    <b>Trainer Name&nbsp;:</b><span class="mx-2">${resp.trainerName ? resp.trainerName : ''}</span>
                                    <a class="float-right">
                                    </a>
                                </li>
                                <li class="list-group-item col-lg-6 col-sm-12">
                                    <b>Trainer's Payment&nbsp;:</b><span class="mx-2">${resp.payTotrainer}</span>
                                    <a class="float-right"></a>
                                </li>
                                <li class="list-group-item col-lg-6 col-sm-12">
                                    <b>Trainer's Payment Date&nbsp;:</b><span class="mx-2">${(resp.trainerPayDate).slice(0, 16)}</span>
                                    <a class="float-right"></a>
                                </li>
                                <li class="list-group-item col-lg-6 col-sm-12">
                                    <b>Payment Type&nbsp;:</b><span class="mx-2">${resp.payment_type}</span>
                                    <a class="float-right"></a>
                                </li>
                                <li class="list-group-item col-lg-6 col-sm-12">
                                    <b>Full Payment&nbsp;:</b><span class="mx-2">${resp.full_payment}</span>
                                    <a class="float-right">
                                    </a>
                                </li>
                                
                                ${resp.payment_type == 'Partition Payment' ?
                            `<li id="oldinput" class="list-group-item col-lg-12 col-sm-12 text-center  mt-5 mb-5 bg-info p-3">
                                        <b style="border-bottom:1px solid #ddd">Partial Payment History</b>
                                    </li>`:
                            `<li class="list-group-item col-lg-6 col-sm-12">
                                        <b>Full Payment Date&nbsp;:</b><span class="mx-2">${resp.totalPayDate}</span>
                                        <a class="float-right"></a>
                                    </li>`
                        }` : ''}
                        
                        ${resp.status != 5 ? `<a href="${PANELURL}lead/edit?id=${resp.id}" class="btn btn-primary btn-block mt-5"><b>Edit Profile</b></a>` : ''}

                        ${respRenewDetails.length > 0 ? `<li class="list-group-item col-lg-12 col-sm-12 text-center  mt-5 mb-5 bg-info p-3" id="renDetail">
                                                    <b style="border-bottom:1px solid">Package Renew History</b>
                                                    <div id = "row" class="row mt-2">
                                                        <div class="col-2">
                                                            <p><b>Renew On</b></p>
                                                        </div>
                                                        <div class="col-2">
                                                            <p><b>Renew Date</b></p>
                                                        </div>
                                                        <div class="col-2">
                                                            <p><b>Renew Amount</b></p>
                                                        </div>
                                                        <div class="col-2">
                                                            <p><b>Renew By</b></p>
                                                        </div>
                                                        <div class="col-4">
                                                            <p><b>Action</b></p>
                                                        </div>
                                                    </div>
                                                </li>`: ''}`);

                $.each(resppaymentDetails, function() {
                    newRowAdd =
                        `<div id = "row" class="row mt-2">
                            <div class="input-group col-6">
                                <p class="amount">Amount:&nbsp;&nbsp;${this.amount}</p>
                            </div>
                            <div class="col-6">
                                <p class="amountDate">Date:&nbsp;&nbsp;${(this.created_date).slice(0, 10)}&nbsp; at &nbsp;${(this.created_date).slice(11)}</p>
                            </div>
                        </div>`;
                    $('#oldinput').append(newRowAdd);
                });

                $.each(respRenewDetails, function() {
                    newRowAdd =
                        `<div id = "row" class="row mt-2">
                            <div class="col-2">
                                <p>${this.created_date}</p>
                            </div>
                            <div class="col-2">
                                <p>${this.renew_date}</p>
                            </div>
                            <div class="col-2">
                                <p>${(this.renew_amount)}</p>
                            </div>
                            <div class="col-2">
                                <p>${(this.created_by)}</p>
                            </div>
                            <div class="col-4">
                                <button title="renew this row" onclick='renewData(${JSON.stringify(this)},${resp.full_payment})' class="btn btn-warning btn-xs">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <a target ="_blank" href="invoice?id=${resp.id}&renew_amount=${this.renew_amount}" title="download invoice" class="btn btn-secondary btn-xs mr5 text-white">
                                    <i class="fa fa-download"></i>
                                </a>
                            </div>
                        </div>`;
                    $('#renDetail').append(newRowAdd);
                });
            })
            .catch(function(err) {
                console.log(err);
            });
    };

    changeReadStatus();

    getData();
</script>

<script>
    let renewData = (row, prev_payment) => {
        $("#exampleModal").modal('show');
        $("#leadId").val(row.lead_id);
        $("#leadPreviousAmount").val(prev_payment - row.renew_amount);
        $("#renewalAmount").val(row.renew_amount);
        $("#renewalDate").val(row.renew_date);
    }

    $(document).ready(function() {
        $('#renewalForm').on('submit', function(event) {
            event.preventDefault();

            var serializedData = $(this).serialize();

            ajaxCallData(PANELURL + 'renewal/editRenewal?type=lead', serializedData, 'POST')
                .then(function(resp) {
                    if (resp.success == 1) {
                        $("#exampleModal").modal('hide');
                        notifyAlert('Data renewed successfully!', 'success');
                        location.reload();
                    } else {
                        notifyAlert('You are not authorized!', 'danger');
                    }
                })
                .catch(function(err) {
                    console.log(err);
                });
        });
    });
</script>
@endsection