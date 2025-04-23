@extends('layouts.layout') {{-- Assuming you're using a layout --}}
@section('content')

<style>
    .list-group-item {
        border: 0px solid rgba(0, 0, 0, .125);
        border-bottom: 1px solid rgba(0, 0, 0, .125);
    }
</style>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6 d-flex">
                    <button class="btn btn-secondary btn-sm" id="back-btn"><i class="fas fa-backward"></i></button>&nbsp;
                    <h1>Yoga Center Details</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">User Yoga Center Details</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-body box-profile">
                            <p class="text-muted text-center my-12">Center Details</p><br><br>
                            <div class="overlay hidden">
                                <i class="fas fa-2x fa-sync-alt"></i>
                            </div>
                            <ul class="list-groups list-group-unbordered mb-3 row align-items-start"></ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="renewalForm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Renewal Date</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body row">
                    <div class="form-group col-lg-6 col-sm-12">
                        <label>Renew Package End Date</label>
                        <input type="hidden" id="leadId" name="leadId" required>
                        <input type="hidden" id="leadPreviousAmount" name="leadPreviousAmount" required>
                        <input type="datetime-local" class="form-control" id="renewalDate" name="renewalDate" required readonly>
                    </div>
                    <div class="form-group col-lg-6 col-sm-12">
                        <label>Package Renewal Amount</label>
                        <input type="number" class="form-control" id="renewalAmount" name="renewalAmount" min="0" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const param = "{{ request('id') }}";
    const PANELURL = "{{ url('/') }}/";

    const getData = () => {
        ajaxCallData(PANELURL + 'yoga-bookings/profile', {
                'bookingId': param
            }, 'POST')
            .then(function(resp) {
                if (resp.success == 1) {
                    const response = resp.data;
                    const paymentDetails = resp.data.paymentDetails;
                    const renewDetails = resp.renew_details;

                    let list = $('.list-groups');
                    list.append(`
                        <li class="list-group-item col-lg-6 col-sm-12"><b>Client Name:</b><span class="mx-2">${response.client_name}</span></li>
                        <li class="list-group-item col-lg-6 col-sm-12"><b>Number:</b><span class="mx-2">${response.client_number}</span></li>
                        <li class="list-group-item col-lg-6 col-sm-12"><b>Email:</b><span class="mx-2">${response.email}</span></li>
                        <li class="list-group-item col-lg-6 col-sm-12"><b>Country:</b><span class="mx-2">${response.country}</span></li>
                        <li class="list-group-item col-lg-6 col-sm-12"><b>State:</b><span class="mx-2">${response.state}</span></li>
                        <li class="list-group-item col-lg-6 col-sm-12"><b>City:</b><span class="mx-2">${response.city}</span></li>
                        <li class="list-group-item col-lg-6 col-sm-12"><b>Center Name:</b><span class="mx-2">${response.event_name}</span></li>
                        <li class="list-group-item col-lg-6 col-sm-12"><b>Start Date:</b><span class="mx-2">${response.s_date}</span></li>
                        <li class="list-group-item col-lg-6 col-sm-12"><b>Package:</b><span class="mx-2">${response.package}</span></li>
                        <li class="list-group-item col-lg-6 col-sm-12"><b>Package End Date:</b><span class="mx-2">${response.e_date}</span></li>
                        <li class="list-group-item col-lg-6 col-sm-12"><b>Payment Type:</b><span class="mx-2">${response.payment_type}</span></li>
                        <li class="list-group-item col-lg-6 col-sm-12"><b>Total Amount Paid:</b><span class="mx-2">${response.totalPayAmount}</span></li>
                    `);

                    if (response.payment_type === 'Partition Payment') {
                        list.append(`<li id="oldinput" class="list-group-item col-lg-12 col-sm-12 text-center mt-5 mb-5 bg-info p-3"><b style="border-bottom:1px solid #ddd">Partial Payment History</b></li>`);
                    } else {
                        list.append(`<li class="list-group-item col-lg-6 col-sm-12"><b>Full Payment Date:</b><span class="mx-2">${response.totalPayDate.slice(0, 10)} at ${response.totalPayDate.slice(11)}</span></li>`);
                    }

                    if (response.status != 5) {
                        list.append(`<a href="${PANELURL}yoga-bookings/edit?id=${response.id}&yoga=1" class="btn btn-primary btn-block"><b>Edit Yoga Center</b></a>`);
                    }

                    if (renewDetails.length > 0) {
                        list.append(`<li class="list-group-item col-lg-12 col-sm-12 text-center mt-5 mb-5 bg-info p-3" id="renDetail">
                        <b style="border-bottom:1px solid">Package Renew History</b>
                        <div class="row mt-2">
                            <div class="col-2"><p><b>Renew On</b></p></div>
                            <div class="col-2"><p><b>Renew Date</b></p></div>
                            <div class="col-2"><p><b>Renew Amount</b></p></div>
                            <div class="col-2"><p><b>Renew By</b></p></div>
                            <div class="col-4"><p><b>Action</b></p></div>
                        </div>
                    </li>`);
                    }

                    paymentDetails.forEach(payment => {
                        $('#oldinput').append(`
                            <div class="row mt-2">
                                <div class="col-6"><p>Amount: ${payment.amount}</p></div>
                                <div class="col-6"><p>Date: ${response.created_date.slice(0, 10)} at ${response.created_date.slice(11)}</p></div>
                            </div>
                        `);
                    });

                    respRenewDetails.forEach(row => {
                        $('#renDetail').append(`
                            <div class="row mt-2">
                                <div class="col-2"><p>${row.created_date}</p></div>
                                <div class="col-2"><p>${row.renew_date}</p></div>
                                <div class="col-2"><p>${row.renew_amount}</p></div>
                                <div class="col-2"><p>${row.created_by}</p></div>
                                <div class="col-4">
                                    <button onclick='renewData(${JSON.stringify(row)}, ${response.totalPayAmount})' class="btn btn-warning btn-xs"><i class="fas fa-edit"></i></button>
                                    <a href="${PANELURL}invoice/yoga?id=${response.id}&renew_amount=${row.renew_amount}" class="btn btn-secondary btn-xs mr5 text-white" target="_blank"><i class="fa fa-download"></i></a>
                                </div>
                            </div>
                        `);
                    });
                }
            })
            .catch(err => console.error(err));
    }

    const renewData = (row, prev_payment) => {
        $("#exampleModal").modal('show');
        $("#leadId").val(row.lead_id);
        $("#leadPreviousAmount").val(prev_payment - row.renew_amount);
        $("#renewalAmount").val(row.renew_amount);
        $("#renewalDate").val(row.renew_date);
    };

    $(document).ready(() => {
        $('#back-btn').click(() => window.location.href = '{{ route("getYoga") }}');

        $('#renewalForm').submit(function(e) {
            e.preventDefault();
            let serializedData = $(this).serialize();
            ajaxCallData(PANELURL + 'renewal/editRenewal?type=yoga', serializedData, 'POST')
                .then(resp => {
                    if (resp.success == 1) {
                        $('#exampleModal').modal('hide');
                        notifyAlert('Data renewed successfully!', 'success');
                        location.reload();
                    } else {
                        notifyAlert('You are not authorized!', 'danger');
                    }
                })
                .catch(err => console.error(err));
        });

        getData();
    });
</script>
@endsection