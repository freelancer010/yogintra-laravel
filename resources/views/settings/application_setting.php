<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <div class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1 class="m-0 text-dark">Application Setting</h1>
            </div>
            <!-- /.col -->
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="<?php echo base_url() ?>admin/admin/dashboard">Dashboard</a></li>
                  <li class="breadcrumb-item"><a href="#">Setting</a></li>
                  <li class="breadcrumb-item active">Application Setting</li>
               </ol>
            </div>
            <!-- /.col -->
         </div>
         <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
   </div>
   <!-- /.content-header -->
   <!-- Main content -->
   <section class="content">
      <div class="container-fluid">
         <!-- Info boxes -->
         <?php echo form_open_multipart('adm/setting/update_application_setting'); ?>
         <div class="card card-default">
            <ul class="nav nav-tabs">
               <li class="nav-item"><a class="nav-link active" href="#general_setting" data-toggle="tab">General Setting</a></li>
               <li class="nav-item"><a class="nav-link" href="#contact_setting" data-toggle="tab">Contact Setting</a></li>
               <li class="nav-item"><a class="nav-link" href="#logo_setting" data-toggle="tab">Logo Setting</a></li>
               <li class="nav-item"><a class="nav-link" href="#payment_setting" data-toggle="tab">Payment Setting</a></li>
               <li class="nav-item"><a class="nav-link" href="#html_head_code_setting" data-toggle="tab">HTML Head Code Setting</a></li>
               <li class="nav-item"><a class="nav-link" href="#social_media_plugin" data-toggle="tab">Social Media Link</a></li>
            </ul>
            <!-- /.card-header -->
            <div class="card-body">
               <div class="tab-content">
                  <div class="tab-pane active" id="general_setting">
                     <div class="form-group row">
                        <label for="inputEmail" class="col-sm-3 col-form-label">Application Name</label>
                        <div class="col-sm-9">
                           <input type="text" class="form-control"  name="app_name"  required="required" placeholder="Enter Application Name" value="<?php echo $app_setting->app_name ?>">
                        </div>
                     </div>
                     <div class="form-group row">
                        <label for="inputEmail" class="col-sm-3 col-form-label">Meta Title</label>
                        <div class="col-sm-9">
                           <input type="text" class="form-control"  name="app_meta_title" value="<?php echo $app_setting->app_meta_title ?>" placeholder="Enter Meta Title">
                        </div>
                     </div>
                     <div class="form-group row">
                        <label for="inputEmail" class="col-sm-3 col-form-label">Meta Description</label>
                        <div class="col-sm-9">
                           <textarea type="text" class="form-control"  name="app_meta_description" placeholder="Enter Meta Description"><?php echo $app_setting->app_meta_description ?></textarea>
                        </div>
                     </div>
                     <div class="form-group row">
                        <label for="inputEmail" class="col-sm-3 col-form-label">Meta Keyword <span style="font-size: 12px;">(Separate with comma(,))</span></label>
                        <div class="col-sm-9">
                           <textarea type="text" class="form-control"  name="app_keywords" placeholder="Enter Meta Keywords"><?php echo $app_setting->app_keywords ?></textarea>
                        </div>
                     </div>
                     <div class="form-group row">
                        <label for="inputEmail" class="col-sm-3 col-form-label">Footer About Us</label>
                        <div class="col-sm-9">
                           <textarea type="text" class="text-editor"  name="footer_about_us"><?php echo $app_setting->footer_about_us ?></textarea>
                        </div>
                     </div>
                   
                     <div class="form-group row">
                        <div class="offset-sm-2 col-sm-10">
                           <button style="float: right;" type="submit" class="btn btn-success setting-submit" name="submit">Save</button>
                        </div>
                     </div>
                  </div>
                  <div class="tab-pane" id="contact_setting">
                     <div class="form-group row">
                        <label for="inputEmail" class="col-sm-3 col-form-label">Address</label>
                        <div class="col-sm-9">
                           <textarea type="text" class="form-control"  name="app_address"  required="required" placeholder="Enter Address"><?php echo $app_setting->app_address ?></textarea>
                        </div>
                     </div>
                     <div class="form-group row">
                        <label for="inputEmail" class="col-sm-3 col-form-label">Mobile No</label>
                        <div class="col-sm-9">
                           <input type="number" class="form-control"  name="app_mobile"  required="required" placeholder="Enter Mobile No" value="<?php echo $app_setting->app_mobile ?>">
                        </div>
                     </div>
                     <div class="form-group row">
                        <label for="inputEmail" class="col-sm-3 col-form-label">Email ID</label>
                        <div class="col-sm-9">
                           <input type="email" class="form-control"  name="app_email"  required="required" placeholder="Enter Email ID" value="<?php echo $app_setting->app_email ?>">
                        </div>
                     </div>
                     <div class="form-group row">
                        <label for="inputEmail" class="col-sm-3 col-form-label">Map Iframe</label>
                        <div class="col-sm-9">
                           <textarea type="text" class="form-control"  name="address_lat"  placeholder="Enter Map Iframe" ><?php echo $app_setting->address_lat ?></textarea>
                        </div>
                     </div>
                     <div class="form-group row">
                        <div class="offset-sm-2 col-sm-10">
                           <button style="float: right;" type="submit" class="btn btn-success setting-submit" name="submit">Save</button>
                        </div>
                     </div>
                  </div>
                  <div class="tab-pane" id="logo_setting">
                     <div class="form-group row">
                        <label for="inputEmail" class="col-sm-3 col-form-label">Application Header Logo</label>
                        <div class="col-sm-9">
                           <input type="file" class="form-control"  name="app_sticky_logo"   id="logo_header">
                        </div>
                     </div>
                     <div class="form-group row">
                        <label for="inputEmail" class="col-sm-3 col-form-label">Preview Application Header Logo</label>
                        <div class="col-sm-9">
                           <img id="header_logo" src="<?php echo base_url() ?><?php echo $app_setting->app_sticky_logo ?>" width="100px" style="border:1px solid #333" >
                        </div>
                     </div>
                     <div class="form-group row">
                        <label for="inputEmail" class="col-sm-3 col-form-label">Application Footer Logo</label>
                        <div class="col-sm-9">
                           <input type="file" class="form-control"  name="app_footer_logo"   id="logo_footer">
                        </div>
                     </div>
                     <div class="form-group row">
                        <label for="inputEmail" class="col-sm-3 col-form-label">Preview Application Footer Logo</label>
                        <div class="col-sm-9">
                           <img id="footer_logo" src="<?php echo base_url() ?><?php echo $app_setting->app_footer_logo ?>" width="100px" style="border:1px solid #333" >
                        </div>
                     </div>
                     <div class="form-group row">
                        <label for="inputEmail" class="col-sm-3 col-form-label">Application Fevicon</label>
                        <div class="col-sm-9">
                           <input type="file" class="form-control"  name="fevicon"   id="fevicon_icon">
                        </div>
                     </div>
                     <div class="form-group row">
                        <label for="inputEmail" class="col-sm-3 col-form-label">Preview Application Fevicon</label>
                        <div class="col-sm-9">
                           <img id="fevicon" src="<?php echo base_url() ?><?php echo $app_setting->fevicon ?>" width="100px" style="border:1px solid #333">
                        </div>
                     </div>
                     <div class="form-group row">
                        <div class="offset-sm-2 col-sm-10">
                           <button style="float: right;" type="submit" class="btn btn-success setting-submit" name="submit">Save</button>
                        </div>
                     </div>
                  </div>
                  <div class="tab-pane" id="payment_setting">
                     <div class="form-group row">
                        <label for="inputEmail" class="col-sm-3 col-form-label">Rozarpay Key ID</label>
                        <div class="col-sm-9">
                           <input type="text" class="form-control"  name="rozar_key_id"  placeholder="Enter Rozarpay Key ID" value="<?php echo $app_setting->rozar_key_id ?>">
                        </div>
                     </div>
                     <div class="form-group row">
                        <label for="inputEmail" class="col-sm-3 col-form-label">Rozarpay Key Secret</label>
                        <div class="col-sm-9">
                           <input type="text" class="form-control"  name="rozar_key_secret"  placeholder="Enter Rozarpay Key Secret" value="<?php echo $app_setting->rozar_key_secret ?>">
                        </div>
                     </div>
                     <div class="form-group row">
                        <div class="offset-sm-2 col-sm-10">
                           <button style="float: right;" type="submit" class="btn btn-success setting-submit" name="submit">Save</button>
                        </div>
                     </div>
                  </div>
                  <div class="tab-pane" id="html_head_code_setting">
                     <div class="form-group row">
                        <label for="inputEmail" class="col-sm-3 col-form-label">Enter Header Code</label>
                        <div class="col-sm-9">
                           <textarea type="text" class="form-control"  name="head_code"  placeholder="Enter Secret Key" rows="10"><?php echo $app_setting->head_code ?></textarea>
                        </div>
                     </div>
                     <div class="form-group row">
                        <div class="offset-sm-2 col-sm-10">
                           <button style="float: right;" type="submit" class="btn btn-success setting-submit" name="submit">Save</button>
                        </div>
                     </div>
                  </div>
                  <div class="tab-pane" id="social_media_plugin">
                     <div class="form-group row">
                        <label for="inputEmail" class="col-sm-3 col-form-label">Enter Facebook URL</label>
                        <div class="col-sm-9">
                           <input type="text" class="form-control"  name="facebook"  placeholder="Enter facebook Url" value="<?php echo $app_setting->facebook ?>">
                        </div>
                     </div>
                     <div class="form-group row">
                        <label for="inputEmail" class="col-sm-3 col-form-label">Enter Twitter URL</label>
                        <div class="col-sm-9">
                           <input type="text" class="form-control"  name="twitter"  placeholder="Enter Twitter Url" value="<?php echo $app_setting->twitter ?>">
                        </div>
                     </div>
                     <div class="form-group row">
                        <label for="inputEmail" class="col-sm-3 col-form-label">Enter Youtube URL</label>
                        <div class="col-sm-9">
                           <input type="text" class="form-control"  name="youtube"  placeholder="Enter Youtube Url" value="<?php echo $app_setting->youtube ?>">
                        </div>
                     </div>
                     <div class="form-group row">
                        <label for="inputEmail" class="col-sm-3 col-form-label">Enter Instagram URL</label>
                        <div class="col-sm-9">
                           <input type="text" class="form-control"  name="instagram"  placeholder="Enter Instagram Url" value="<?php echo $app_setting->instagram ?>">
                        </div>
                     </div>
                     <div class="form-group row">
                        <label for="inputEmail" class="col-sm-3 col-form-label">Enter Telegram URL</label>
                        <div class="col-sm-9">
                           <input type="text" class="form-control"  name="telegram"  placeholder="Enter Telegram Url" value="<?php echo $app_setting->telegram ?>">
                        </div>
                     </div>
                     <div class="form-group row">
                        <div class="offset-sm-2 col-sm-10">
                           <button style="float: right;" type="submit" class="btn btn-success setting-submit" name="submit">Save</button>
                        </div>
                     </div>
                  </div>
                  
                 
                  <?php echo form_close() ?>
               </div>
               <!-- /.row -->
            </div>
            <!-- /.card-body -->
         </div>
         <!-- /.row -->
      </div>
      <!--/. container-fluid -->
   </section>
   <!-- /.content -->
</div>
<!-- /.content-wrapper -->


<script>
   
   function logo_header(input) {
     if (input.files && input.files[0]) {
       var reader = new FileReader();
       reader.onload = function(e) {
         $('#header_logo').attr('src', e.target.result);
       }
       reader.readAsDataURL(input.files[0]);
     }
   }
   $("#logo_header").change(function() {
     logo_header(this);
   });
   function logo_footer(input) {
     if (input.files && input.files[0]) {
       var reader = new FileReader();
       reader.onload = function(e) {
         $('#footer_logo').attr('src', e.target.result);
       }
       reader.readAsDataURL(input.files[0]);
     }
   }
   $("#logo_footer").change(function() {
     logo_footer(this);
   });
   function fevicon_icon(input) {
     if (input.files && input.files[0]) {
       var reader = new FileReader();
       reader.onload = function(e) {
         $('#fevicon').attr('src', e.target.result);
       }
       reader.readAsDataURL(input.files[0]);
     }
   }
   $("#fevicon_icon").change(function() {
     fevicon_icon(this);
   });
   function admin_panel_logo(input) {
     if (input.files && input.files[0]) {
       var reader = new FileReader();
       reader.onload = function(e) {
         $('#admin_logo').attr('src', e.target.result);
       }
       reader.readAsDataURL(input.files[0]);
     }
   }
   $("#admin_panel_logo").change(function() {
     admin_panel_logo(this);
   });
   
</script>
