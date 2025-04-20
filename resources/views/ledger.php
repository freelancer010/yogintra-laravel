<?php
$this->load->view('includes/header');
?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Ledger</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">Home</li>
                        <li class="breadcrumb-item active">Ledger</li>
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
                                        <button type="button" class="btn btn-sm btn-success mr-3 " onclick=filter()>
                                            Generate&nbsp;&nbsp;<i class="fas fa-arrow-right"></i>
                                        </button>
                                        <!-- <button type="button" class="btn btn-danger mr-3" onclick=reset()>reset</button> -->
                                    </div>
                                    <div class="d-flex mr-1 align-items-center">
                                        <!-- <label for="fromDate" class="exampleInputEmail1 mr-1 text-muted ">From</label> -->
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
                        <div class="card-body">
                            <select id="class_type" name="class" class="form-control editInputBox col-lg-4 m-auto">
                                <option value='' selected>Select Your Class type</option>

                                <option value="Home Visit Yoga">Home Visit Yoga</option>
                                <option value="Private Online Yoga">Private Online Yoga</option>
                                <option value="Group Online Yoga">Group Online Yoga</option>
                                <option value="Corporate Yoga">Corporate Yoga</option>
                                <option value="Retreat">Retreat</option>
                                <option value="Workshop">Workshop</option>
                                <option value="TTC">TTC</option>
                                <option value="Yoga Center">Yoga Center</option>
                            </select>
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Client Name</th>
                                        <th>Income</th>
                                        <th style="width:180px !important;">Date</th>
                                        <th>Trainer Name</th>
                                        <th>Expenses</th>
                                        <th style="width:180px !important;">Date</th>
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
<script>
    let filter = () => {
        let toDate = $("#toDate").val();
        let fromDate = $("#fromDate").val();
        getData('all', fromDate, toDate);
    }

    let reset = () => {
        let toDate = $("#toDate").val('');
        let fromDate = $("#fromDate").val('');
        getData();
    }

    let getData = (class_type = 'all', startDate = '', endDate = '') => {
        var apiUrl = PANELURL + 'ledger';
        ajaxCallData(apiUrl, {
                'class_type': class_type,
                startDate: startDate,
                endDate: endDate
            }, 'POST')
            .then(function(result) {
                resp = JSON.parse(result);
                if (resp.success == 1) {
                    response = resp.data;
                    let cols = [{
                            data: "name"
                        },
                        {
                            data: "full_payment"
                        },
                        {
                            data: null,
                            render: function(data, type, row) {
                                return (row.created_date != '' && row.created_date != undefined) ? row.created_date.slice(0, 10) : '';
                            }
                        },
                        {
                            data: "trainerName"
                        },
                        {
                            data: "payTotrainer"
                        },
                        {
                            data: null,
                            render: function(data, type, row) {
                                return (row.trainerPayDate != '' && row.trainerPayDate != undefined) ? row.trainerPayDate.slice(0, 10) : '';
                            }
                        }
                    ]
                    createDataTable("example1", response, cols, 2);
                } else {
                    createDataTable("example1", '', '');
                }
            })
            .catch(function(err) {
                console.log(err);
            });
    };
    getData();

    $('select').on('change', function() {
        getData(this.value);
    });
</script>