

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>



<footer class="main-footer">
    <strong>Copyright &copy; 2023-2030 <a href="#">YogIntra</a>.</strong>
    All rights reserved.
</footer>

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
</aside>
<!-- /.control-sidebar -->
</div>
<!-- <?= base_url('assets/') ?>wrapper -->

<!-- Bootstrap 4 -->
<script src="<?= base_url('assets/') ?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- daterangepicker -->
<script src="<?= base_url('assets/') ?>plugins/moment/moment.min.js"></script>
<script src="<?= base_url('assets/') ?>plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="<?= base_url('assets/') ?>plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>

<!-- overlayScrollbars -->
<script src="<?= base_url('assets/') ?>plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="<?= base_url('assets/') ?>dist/js/adminlte.js"></script>

<script src="<?= base_url('assets/') ?>js/getCountryStateCityApi.js"></script>

<!-- Bootstrap 4 -->
<script src="<?= base_url('assets/') ?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables  & Plugins -->
<script src="<?= base_url('assets/') ?>plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url('assets/') ?>plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url('assets/') ?>plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= base_url('assets/') ?>plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="<?= base_url('assets/') ?>plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url('assets/') ?>plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="<?= base_url('assets/') ?>plugins/jszip/jszip.min.js"></script>
<script src="<?= base_url('assets/') ?>plugins/pdfmake/pdfmake.min.js"></script>
<script src="<?= base_url('assets/') ?>plugins/pdfmake/vfs_fonts.js"></script>
<script src="<?= base_url('assets/') ?>plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="<?= base_url('assets/') ?>plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="<?= base_url('assets/') ?>plugins/datatables-buttons/js/buttons.colVis.min.js"></script>

<script src="<?= base_url('assets/') ?>plugins/summernote/summernote-bs4.min.js"></script>
<script type="text/javascript">
  $(function () {
    // Summernote
    $('.text-editor').summernote({
      toolbar: [
    // [groupName, [list of button]]
        ['style', ['bold', 'italic', 'underline', 'clear']],
        ['font', ['strikethrough', 'superscript', 'subscript']],
        ['fontsize', ['fontsize']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['height', ['height']],
        ['table', ['table']],
        ['insert', ['link', 'picture', 'video']],
        ['view', ['fullscreen', 'codeview', 'help']],
      ],
      height: 200,
    });
  })
</script>

<script>
    $('button#DeleteRow').css('display', 'none');

    function createDataTable(table_id, jsonData, cols,ord = 0) {
        $('#' + table_id).DataTable().clear().destroy();
        if (jsonData.length > 0) {
            $('#' + table_id).DataTable({
                data: jsonData,
                columns: cols,
                "order": [[ord, 'desc']],
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                dom: 'Bfrtip',
                buttons: [
                     'csv',  'pdf'
                ],
                'columnDefs': [
                    {
                        "targets": 0, // your case first column
                        "className": "text-left",
                    }
                ]
            });
        } else {
            $('#' + table_id).DataTable({
                "paging": false,
                "lengthChange": false,
                "searching": false,
                "ordering": false,
                "info": false,
                "autoWidth": false,
                "responsive": true,
            });
        }
    }


    function ajaxCallData(url, param, method) {
        return new Promise(function (resolve, reject) {
            // console.log(param);
            $.ajax({
                type: method,
                url: url,
                data: param,
                beforeSend: function () {
                    $('.overlay').removeClass('hidden');
                },
                complete: function(request, status) {
                    $('.overlay').addClass('hidden');
                    // var location = request.getResponseHeader("current-location");
                },
                success: function (data) {
                    resolve(data);
                },
                error: function (e) {
                    reject(e);
                }
            });
        });
    }

    function notifyAlert(message,color){
        $('#toastsContainerTopRight').html(`
            <div class="toast bg-${color} fade show" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-body">${message}</div>
            </div>
        `);
        setTimeout(() => {
            $('#toastsContainerTopRight').html('');
        }, 2000);
    }

    function isJSON(str) {
        if(typeof(str) === "boolean"){ 
            return false; 
        }

        try {
            JSON.parse(str);
        } catch (e) {
            return false;
        }
        return true;
    }

    let redirect = (location)=>{
        window.location.href = PANELURL+location;
    }
</script>

</body>

</html>