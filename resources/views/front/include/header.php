<?php $visual_setting = $this->setting_model->get_visual_setting();
if (!empty($title)) {
    $display_title = $title;
} else {
    $display_title = $app_setting->app_name;
}
?>

<!DOCTYPE html>
<html dir="ltr" lang="en">
<head>

<!-- Meta Tags -->
<meta name="viewport" content="width=device-width,initial-scale=1.0"/>
<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
<meta name="description" content="Yoga | Health Beauty & Yoga Responsive HTML5 Template" />
<meta name="keywords" content="care, clinic, corporate, dental, dentist, doctor" />
<meta name="author" content="ThemeMascot" />

<!-- Page Title -->
<title><?php echo $display_title; ?></title>

<!-- Favicon and Touch Icons -->
<link href="<?php echo base_url(); ?><?php echo $app_setting->fevicon; ?>" rel="shortcut icon" type="image/png">
<link href="<?php echo base_url(); ?><?php echo $app_setting->fevicon; ?>" rel="apple-touch-icon">
<link href="<?php echo base_url(); ?><?php echo $app_setting->fevicon; ?>" rel="apple-touch-icon" sizes="72x72">
<link href="<?php echo base_url(); ?><?php echo $app_setting->fevicon; ?>" rel="apple-touch-icon" sizes="114x114">
<link href="<?php echo base_url(); ?><?php echo $app_setting->fevicon; ?>" rel="apple-touch-icon" sizes="144x144">

<!-- Stylesheet -->
<link href="<?php echo front_css(); ?>css/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="<?php echo front_css(); ?>css/jquery-ui.min.css" rel="stylesheet" type="text/css">
<link href="<?php echo front_css(); ?>css/animate.css" rel="stylesheet" type="text/css">
<link href="<?php echo front_css(); ?>css/css-plugin-collections.css" rel="stylesheet"/>
<!-- CSS | menuzord megamenu skins -->
<link href="<?php echo front_css(); ?>css/menuzord-megamenu.css" rel="stylesheet"/>
<link id="menuzord-menu-skins" href="<?php echo front_css(); ?>css/menuzord-skins/menuzord-bottom-trace.css" rel="stylesheet"/>
<!-- CSS | Main style file -->
<link href="<?php echo front_css(); ?>css/style-main.css" rel="stylesheet" type="text/css">
<!-- CSS | Preloader Styles -->
<link href="<?php echo front_css(); ?>css/preloader.css" rel="stylesheet" type="text/css">
<!-- CSS | Custom Margin Padding Collection -->
<link href="<?php echo front_css(); ?>css/custom-bootstrap-margin-padding.css" rel="stylesheet" type="text/css">
<!-- CSS | Responsive media queries -->
<link href="<?php echo front_css(); ?>css/responsive.css" rel="stylesheet" type="text/css">
<!-- CSS | Style css. This is the file where you can place your own custom css code. Just uncomment it and use it. -->
<!-- <link href="<?php echo front_css(); ?>css/style.css" rel="stylesheet" type="text/css"> -->

<!-- Revolution Slider 5.x CSS settings -->
<link  href="<?php echo front_css(); ?>js/revolution-slider/css/settings.css" rel="stylesheet" type="text/css"/>
<link  href="<?php echo front_css(); ?>js/revolution-slider/css/layers.css" rel="stylesheet" type="text/css"/>
<link  href="<?php echo front_css(); ?>js/revolution-slider/css/navigation.css" rel="stylesheet" type="text/css"/>

<!-- CSS | Theme Color -->
<link href="<?php echo front_css(); ?>css/colors/theme-skin-color-set1.css" rel="stylesheet" type="text/css">

<!-- external javascripts -->
<script src="<?php echo front_css(); ?>js/jquery-2.2.4.min.js"></script>
<script src="<?php echo front_css(); ?>js/jquery-ui.min.js"></script>
<script src="<?php echo front_css(); ?>js/bootstrap.min.js"></script>
<!-- JS | jquery plugin collection for this theme -->
<script src="<?php echo front_css(); ?>js/jquery-plugin-collection.js"></script>

<!-- Revolution Slider 5.x SCRIPTS -->
<script src="<?php echo front_css(); ?>js/revolution-slider/js/jquery.themepunch.tools.min.js"></script>
<script src="<?php echo front_css(); ?>js/revolution-slider/js/jquery.themepunch.revolution.min.js"></script>

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
</head>
<style type="text/css">
:root {
  --theme-color-1 : <?php echo $visual_setting->color_1; ?>;
  --theme-color-2 : <?php echo $visual_setting->color_2; ?>;
  }
  </style>
<body class="">
<div id="wrapper">
  <!-- preloader -->
  <div id="preloader">
    <div id="spinner">
      <div class="preloader-dot-loading">
        <div class="cssload-loading"><i></i><i></i><i></i><i></i></div>
      </div>
    </div>
    <div id="disable-preloader" class="btn btn-default btn-sm">Disable Preloader</div>
  </div>
  
   <!-- Header -->
  <header id="header" class="header">
    <div class="header-nav header-nav-center bg-white header-nav-centered-logo navbar-scrolltofixed navbar-sticky-animated">
      <div class="header-nav-wrapper">
        <div class="container">
          <nav id="menuzord" class="menuzord blue no-bg"><a class="menuzord-brand mb-15" href="index-mp-layout1.html"><img src="<?php echo base_url(); ?><?php echo $app_setting->app_sticky_logo; ?>" alt="<?php echo $app_setting->app_name; ?>"></a>
            <ul class="menuzord-menu">
              <li class="active"><a href="<?php echo base_url(); ?>">Home</a>
              </li>
              <li><a href="#">Yoga Booking</a>
                <ul class="dropdown">
                  <li><a href="features-preloader.html">Preloaders</a></li>
                </ul>
              </li>
              <li><a href="#">Yoga Center</a></li>

              <li><a href="#">TTC</a></li>

              <li><a href="javascript:void(0)">Retreat </a></li>

              <li><a href="javascript:void(0)">Trainer</a></li>
              <li><a href="javascript:void(0)">Hire</a></li>
              <li><a href="javascript:void(0)">Dietician</a></li>
              <li><a href="#">About</a>
                <ul class="dropdown">
                  <li><a href="features-preloader.html">Preloaders</a></li>
                </ul>
              </li>
            </ul>
          </nav>
        </div>
      </div>
    </div>
  </header>