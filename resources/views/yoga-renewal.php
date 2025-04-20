<?php
$this->load->view('includes/header');
?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Renewal</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Renewal</li>
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
                                        <button type="button" class="btn btn-sm btn-success mr-3 " onclick=filter()>
                                            Generate&nbsp;&nbsp;<i class="fas fa-arrow-right"></i>
                                        </button>
                                    </div>
                                    <div class="d-flex mr-1 align-items-center">
                                        <input style="height: 32px;" type="date" class="form-control mr-3" id="fromDate"
                                            max="<?php echo date('Y-m-d'); ?>">
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <label for="toDate" class="exampleInputEmail1 mt-1 mr-3 text-muted">To</label>
                                        <input style="height: 32px;" type="date" class="form-control mr-1" id="toDate"
                                            max="<?php echo date('Y-m-d'); ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th style="width:2% !important;">ID</th>
                                        <th style="width:10% !important;">Client Name</th>
                                        <th style="width:6% !important;">Client Number</th>
                                        <th style="width:5% !important;">Country</th>
                                        <th style="width:5% !important;">State</th>
                                        <th style="width:5% !important;">City</th>
                                        <th style="width:10% !important;">Yoga Center</th>
                                        <th style="width:10% !important;">Date</th>
                                        <th style="width:5% !important;">Package Expire Date</th>
                                        <th style="width:5% !important;">Action</th>
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
<?php
$this->load->view('includes/footer');
?>

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
                            placeholder="Enter Renewal Date" value="">
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

<script>
    let filter = () => {
        let toDate = $("#toDate").val();
        let fromDate = $("#fromDate").val();
        getData(fromDate, toDate);
    }

    let reset = () => {
        let toDate = $("#toDate").val('');
        let fromDate = $("#fromDate").val('');
        getData();
    }

    let getData = (startDate = '', endDate = '') => {
        var apiUrl = PANELURL + 'renewal/view';
        ajaxCallData(apiUrl, {
            startDate: startDate,
            endDate: endDate,
            type: "<?= $_GET['type'] ?>"
        }, 'POST')
            .then(function (result) {
                resp = JSON.parse(result);
                if (resp.success == 1) {
                    response = resp.data;
                    let cols = [{
                        data: null,
                        render: function (data, type, row) {
                            return row.id;
                        }
                    },
                    {
                        data: null,
                        render: function (data, type, row) {
                            return `<a href="${PANELURL}yoga-bookings/view?id=${row.id}">${row.client_name}</a>`;
                        }
                    },
                    {
                        data: "client_number"
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
                        data: "event_name"
                    },
                    {
                        data: "created_date"
                    },
                    {
                        data: "e_date"
                    },
                    {
                        data: null,
                        render: function (data, type, row) {
                            return `<div class="d-flex justify-content-between">
                                            <button title="renew this row" onclick="renewData(${row.id},${row.totalPayAmount})" class="btn btn-success btn-xs">
                                                <i class="fas fa-retweet"></i>
                                            </button>
                                            <button href="#" title="skip this row" onclick="skipRenew(${row.id})" class="btn btn-danger btn-xs">
                                                <i class="fas fa-forward"></i>
                                            </button>
                                        </div>`;
                        }
                    }
                    ]
                    createDataTable("example1", response, cols);
                    $('.buttons-pdf, .buttons-csv').css('height', '33px');

                } else {
                    createDataTable("example1", '', '');
                }
            })
            .catch(function (err) {
                console.log(err);
            });
    };

    getData();

    let skipRenew = (id) => {
        let postData = {
            'id': id,
        }
        ajaxCallData(PANELURL + 'renewal/skipRenew?type=yoga', postData, 'POST')
            .then(function (result) {
                jsonCheck = isJSON(result);
                if (jsonCheck == true) {
                    resp = JSON.parse(result);
                    if (resp.success == 1) {
                        getData();
                        notifyAlert('Renewal Skipped Successfully!', 'success');
                    } else {
                        notifyAlert('You are not authorized!', 'danger');
                    }
                } else {
                    notifyAlert('You are not authorized!', 'danger');
                }

            })
            .catch(function (err) {
                console.log(err);
            });
    };

    let renewData = (id,amount) => {
        $("#exampleModal").modal('show');
        $("#leadId").val(id);
        $("#leadPreviousAmount").val(amount);
    }
</script>

<script>
    $(document).ready(function () {
        $('#renewalForm').on('submit', function (event) {
            event.preventDefault();

            var serializedData = $(this).serialize();

            ajaxCallData(PANELURL + 'renewal/editRenewal?type=yoga', serializedData, 'POST')
                .then(function (result) {
                    jsonCheck = isJSON(result);
                    if (jsonCheck == true) {
                        resp = JSON.parse(result);
                        if (resp.success == 1) {
                            $("#exampleModal").modal('hide');

                            getData();
                            notifyAlert('Data renewed successfully!', 'success');
                        } else {
                            notifyAlert('You are not authorized!', 'danger');
                        }
                    } else {
                        notifyAlert('You are not authorized!', 'danger');
                    }
                })
                .catch(function (err) {
                    console.log(err);
                });
        });
    });
</script>