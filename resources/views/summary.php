<?php
$this->load->view('includes/header');
?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Summary</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">Home</li>
                        <li class="breadcrumb-item active">Summary</li>
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
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th style="width:250px !important">Particular Class Name</th>
                                        <th style="text-align:center">Credit Amount</th>
                                        <th style="text-align:center">Debit Amount</th>
                                        <th style="text-align:center">Profit Amount</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th style="text-align:center">TOTAL</th>
                                        <th style="text-align:center" id="totalCredit">Credit Amount</th>
                                        <th style="text-align:center" id="totalDebit">Debit Amount</th>
                                        <th style="text-align:center" id="totalProfit">Profit Amount</th>
                                    </tr>
                                </tfoot>
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
        var apiUrl = PANELURL + 'summary';
        ajaxCallData(apiUrl, { 'class_type': class_type, startDate: startDate, endDate: endDate }, 'POST')
            .then(function (result) {
                resp = JSON.parse(result);
                if (resp.success == 1) {
                    response = resp.data;
                    let cols = [
                        { data: "class_type" },
                        { data: "full_payment" },
                        { data: "payTotrainer" },
                        {
                            data: null,
                            render: function (data, type, row) {
                                var profit = parseInt(row.full_payment - row.payTotrainer)
                                return `${profit > 0 ? profit : 0}`;
                            }
                        }
                    ]
                    createDataTable("example1", response, cols);
                    $("#totalCredit").text(resp.totalCredit);
                    $("#totalDebit").text(resp.totalDebit);
                    $("#totalProfit").text(parseInt(resp.totalCredit - resp.totalDebit));
                } else {
                    createDataTable("example1", '', '');
                }
                $('td').css('text-align', 'center');
                $('.buttons-pdf, .buttons-csv').css('height', '33px');
            })
            .catch(function (err) {
                console.log(err);
            });
    };
    getData();
</script>