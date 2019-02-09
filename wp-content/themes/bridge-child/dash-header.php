<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <?php if(qode_options()->getOption('favicon_image') !== ''){ ?>
        <link rel="shortcut icon" type="image/x-icon" href="<?php echo esc_url($qode_options_proya['favicon_image']); ?>">
        <link rel="apple-touch-icon" href="<?php echo esc_url($qode_options_proya['favicon_image']); ?>"/>
    <?php } ?>
  <title>
      COETE | <?php echo get_the_title(); ?>
  </title>
  <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Material+Icons" />
  <link href="https://fonts.googleapis.com/css?family=Raleway:400,500,600,700" rel="stylesheet">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
  <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.1/themes/base/minified/jquery-ui.min.css" type="text/css" />
  <!-- CSS Files -->
  <link href="<?php echo get_stylesheet_directory_uri(); ?>/style.css" rel="stylesheet" />
  <link href="<?php echo get_stylesheet_directory_uri(); ?>/css/material-dashboard.css?v=2.1.1" rel="stylesheet" />
  <link href="<?php echo get_stylesheet_directory_uri(); ?>/css/flags.css" rel="stylesheet" type="text/css" />
    <script src="<?php echo get_stylesheet_directory_uri(); ?>/js/prettify.min.js"></script>
    <script type="text/javascript" src="<?php echo site_url(); ?>/wp-includes/js/jquery/jquery.js?ver=1.12.4"></script>
</head>

<body class="">
<div class="wrapper " id="wrapper">
    <div class="sidebar" data-color="purple" data-background-color="white" data-image="../assets/img/sidebar-1.jpg">
      <div class="logo">
          <a href="<?php echo site_url(); ?>" class="simple-text logo-normal">
        	<img src="<?php echo site_url(); ?>/wp-content/uploads/2018/12/logo-1.png" style="width: 150px;">
        </a>
      </div>
      <div class="sidebar-wrapper">
        <ul class="nav">
          <li class="nav-item <?php if(is_page('dashboard')) { ?>active<?php } ?>">
            <a class="nav-link" href="<?php echo site_url(); ?>/dashboard/">
              <i class="material-icons">dashboard</i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="nav-item <?php if(is_page('edit-profile')) { ?>active<?php } ?>">
            <a class="nav-link" href="<?php echo site_url(); ?>/edit-profile/">
              <i class="material-icons">person</i>
              <p>Edit Profile</p>
            </a>
          </li>
          <?php $current_user = wp_get_current_user(); ?>
          <?php if ($current_user->roles[0] == 'operator') { ?>
          <li class="nav-item <?php if(is_page('operator-entry')) { ?>active<?php } ?>">
            <a class="nav-link" href="<?php echo site_url(); ?>/operator-entry/">
              <i class="material-icons">person</i>
              <p>Operator Entry</p>
            </a>
          </li>
          <?php } ?>
          <?php if ($current_user->roles[0] == 'trainer') { ?>
          <li class="nav-item <?php if(is_page('trainer-entry')) { ?>active<?php } ?>">
            <a class="nav-link" href="<?php echo site_url(); ?>/trainer-entry/">
              <i class="material-icons">person</i>
              <p>Trainer Entry</p>
            </a>
          </li>
          <?php } ?>
          <?php if ($current_user->roles[0] == 'evaluator') { ?>
          <li class="nav-item <?php if(is_page('evaluator-entry')) { ?>active<?php } ?>">
            <a class="nav-link" href="<?php echo site_url(); ?>/evaluator-entry/">
              <i class="material-icons">person</i>
              <p>Evaluator Entry</p>
            </a>
          </li>
          <?php } ?>
          <?php if ($current_user->roles[0] == 'operator' || $current_user->roles[0] == 'evaluator' || 
                    $current_user->roles[0] == 'trainer' || $current_user->roles[0] == 'company-admin' || 
                    $current_user->roles[0] == 'union-admin') { ?>
          <li class="nav-item <?php if(is_page('experience-table')) { ?>active<?php } ?>">
            <a class="nav-link" href="<?php echo site_url(); ?>/experience-table/">
              <i class="material-icons">table_chart</i>
              <p>Experience Table</p>
            </a>
          </li>
          <?php } if ($current_user->roles[0] == 'company-admin' || 
                    $current_user->roles[0] == 'union-admin') { ?>
          <li class="nav-item <?php if(is_page('operator-search')) { ?>active<?php } ?>">
            <a class="nav-link" href="<?php echo site_url(); ?>/operator-search/">
              <i class="material-icons">search</i>
              <p>Search</p>
            </a>
          </li>
          <?php if ($current_user->roles[0] == 'company-admin') { ?>
          <li class="nav-item <?php if(is_page('branch-roster')) { ?>active<?php } ?>">
            <a class="nav-link" href="<?php echo site_url(); ?>/branch-roster/">
              <i class="material-icons">list</i>
              <p>Branch Roster</p>
            </a>
          </li>
          <?php } else { ?>
          <li class="nav-item <?php if(is_page('local-roster')) { ?>active<?php } ?>">
            <a class="nav-link" href="<?php echo site_url(); ?>/local-roster/">
              <i class="material-icons">list</i>
              <p>Local Roster</p>
            </a>
          </li>
          <?php } ?>
          <li class="nav-item <?php if(is_page('evaluator-roster')) { ?>active<?php } ?>">
            <a class="nav-link" href="<?php echo site_url(); ?>/evaluator-roster/">
              <i class="material-icons">list</i>
              <p>Evaluator Roster</p>
            </a>
          </li>
          <li class="nav-item <?php if(is_page('operator-roster')) { ?>active<?php } ?>">
            <a class="nav-link" href="<?php echo site_url(); ?>/operator-roster/">
              <i class="material-icons">list</i>
              <p>Operator Roster</p>
            </a>
          </li>
          <li class="nav-item <?php if(is_page('trainer-roster')) { ?>active<?php } ?>">
            <a class="nav-link" href="<?php echo site_url(); ?>/trainer-roster/">
              <i class="material-icons">list</i>
              <p>Trainer Roster</p>
            </a>
          </li>
          <li class="nav-item <?php if(is_page('send-invite')) { ?>active<?php } ?>">
            <a class="nav-link" href="<?php echo site_url(); ?>/send-invite/">
              <i class="material-icons">email</i>
              <p>Signup Invitation</p>
            </a>
          </li>
          <?php } ?>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo wp_logout_url(home_url()) ?>">
              <i class="material-icons">logout</i>
              <p>Logout</p>
            </a>
          </li>
        </ul>
      </div>
    </div>


<div class="main-panel dashboard-page">
        <!-- Navbar -->
      <nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
        <div class="container-fluid">
            <div class="navbar-wrapper" id="breadcrumbs">
            <?php if (is_page('dashboard')) { ?>
            <a href="<?php echo site_url(); ?>">Home</a><i class="fa fa-angle-right"></i><a href="<?php the_permalink(); ?>"><b><?php the_title(); ?></b></a>
            <?php } else { ?>
            <a href="<?php echo site_url(); ?>/dashboard">Dashboard</a><i class="fa fa-angle-right"></i><a href="<?php the_permalink(); ?>"><b><?php the_title(); ?></b></a>
            <?php } ?>
          </div> 
          <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
            <span class="sr-only">Toggle navigation</span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
          </button>
          <div class="collapse navbar-collapse justify-content-end">
            <!--<form class="navbar-form">
              <div class="input-group no-border">
                <input type="text" value="" class="form-control" placeholder="Search...">
                <button type="submit" class="btn btn-white btn-round btn-just-icon">
                  <i class="material-icons">search</i>
                  <div class="ripple-container"></div>
                </button>
              </div>
            </form>-->
            <ul class="navbar-nav">
              <li class="nav-item dropdown">
                <a class="nav-link" href="#pablo" id="navbarDropdownProfile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="material-icons" style="font-size: 2.3rem;">account_circle</i>
                  <p class="d-lg-none d-md-block">
                    Account
                  </p>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownProfile">
                  <!--<a class="dropdown-item" href="#">Settings</a>
                  <div class="dropdown-divider"></div>-->
                  <a class="dropdown-item" href="<?php echo wp_logout_url(home_url()) ?>">Log out</a>
                </div>
              </li>
            </ul>
            <div class="navbar-nav welcome-comments">
                <?php 
                    $role = $current_user->roles[0];
                    if ($role == 'union-admin') {
                        $role = 'Union Admin';
                    }
                    if ($role == 'company-admin') {
                        $role = 'Company Admin';
                    }
                ?>
                <h4>Welcome <?php echo $current_user->display_name; ?><br> <small><?php echo $role; ?></small></h4>
            </div>
          </div>
        </div>
      </nav>
      <!-- End Navbar -->
      <!-- link sec -->
      
      <div class="dashboard-page-heading">
        <h2><?php the_title(); ?></h2>
      </div>

      