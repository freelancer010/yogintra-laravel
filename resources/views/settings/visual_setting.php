<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark"><?php echo $title ?></h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url() ?>admin/admin/dashboard">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="#"><?php echo ucwords(str_replace("_", " ", $this->uri->segment(2))); ?></a></li>
              <li class="breadcrumb-item active"><?php echo ucwords(str_replace("_", " ", $title)); ?></li>

            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Info boxes -->
        <div class="card card-default">
          <div class="card-header">
            <h3 class="card-title">Update <?php echo $title ?></h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
            </div>
          </div>
          <!-- /.card-header -->
          <?php echo form_open('admin/setting/update_visual_setting'); ?>
          <div class="card-body">
            <div class="row">
               <!-- BlueBackground -->
               <div class="col-md-6">
                  <div class="form-group">
                     <label class="text-dark" for="blue_bg">Color 1:</label>
                     <input name="color_1" class="form-control" type="color" value="<?php echo $visual_setting->color_1; ?>"/>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group">
                     <label class="text-dark" for="blue_bg">Color 2:</label>
                     <input name="color_2" class="form-control" type="color" value="<?php echo $visual_setting->color_2; ?>"/>
                  </div>
               </div>

               <div class="col-md-12">
                  <div class="form-group">
                     <button name="submit" style="float: right" type="submit" class="btn btn-success">Update</button>
                  </div>
                </div>
            </div>
            <?php echo form_close() ?>
          </div>
        </div>
      </div>
    </section>
  </div>