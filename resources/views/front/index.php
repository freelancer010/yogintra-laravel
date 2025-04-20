

<div id="header-stay">
<?php $this->load->view('front/include/header'); ?>
</div>
<div class="preloader">
  <div class="spinner"></div>
</div>
<div id="main-content">
   <?php $this->load->view('front/'.$page);?>
</div>

<div id="footer-stay">
<?php $this->load->view('front/include/footer'); ?>
</div>

<script src="<?php echo front_css(); ?>js/ajaxify.min.js"></script>

<script type="text/javascript">
   let myAjaxify = new Ajaxify({
       elements: "#main-content, #header-stay, #footer-stay",
       verbosity: 2,
       scrolltop : true,
       pluginon: !0,
       requestDelay: 0,
       intevents: true,
       inline:true,
       style:true,
       asyncdef : true,
       alwayshints: "",
   });

</script>
