<!-- DataTables -->
<link rel="stylesheet" href="<?= base_url() ?>assets/plugins/datatables-bs4/css/dataTables.bootstrap4.css"> 
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>User List</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">User List</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>


    <!-- Main content -->
    <section class="content mt10">
    	<div class="card">
    		<div class="card-body">
               <!-- Load Admin list (json request)-->
               <!-- <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Client Name</th>
                            <th>Client Number</th>
                            <th>Email Id</th>
                            <th>Country</th>
                            <th>Type Of Class</th>
                            <th>Call From</th>
                            <th>Call To</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table> -->
               <div class="data_container"></div>
           </div>
       </div>
    </section>
    <!-- /.content -->
</div>



<!-- DataTables -->
<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.js')?>"></script>
<script src="<?= base_url('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.js')?>"></script>
<script>
  $(function () {
    $("#example1").DataTable();
  });

</script> 

<script>
//------------------------------------------------------------------
function filter_data(){
    $('.data_container').html(
        '<div class="text-center"><img src="<?=base_url('assets/front/css/lightbox/loading.gif')?>"/></div>'
        );
    $.post(
        '<?=base_url('admin/filterdata')?>',
        $('.filterdata').serialize(),
        function(){
            $('.data_container').load(
                '<?=base_url('admin/list_data')?>'
                );
        }
    );
}
//------------------------------------------------------------------
function load_records(){
    $('.data_container').html('<div class="text-center"><img src="<?=base_url('assets/front/css/lightbox/loading.gif')?>"/></div>');
    $('.data_container').load('<?=base_url('admin/list_data')?>');
}

load_records();

//---------------------------------------------------------------------
$("body").on("change",".tgl_checkbox",function(){
    $.post('<?=base_url("admin/change_status")?>',
    {
        '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>',
        id : $(this).data('id'),
        status : $(this).is(':checked')==true?1:0
    },
    function(data){
        $.notify("Status Changed Successfully", "success");
    });
});
</script>
