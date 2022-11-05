<?php 
    ob_start();
    session_start();
    
    require 'vendor/autoload.php';
    require 'init.php';
    use cgc\platform\Middleware\AuthMiddleware; 
    use cgc\platform\controllers\ClubController;
    // check if the current user authorized to get the access

    $auth = new AuthMiddleware(); 

    $auth->AuthGuard();
    
    $path =  '/app/views';

    // setup profile pic
    if(isset($_SESSION['log_data'][0]['club_id'])) {
      
        $club = new ClubController();
        $clubinfo = $club->GetClubInfo($_SESSION['log_data'][0]['club_id']);
        
        
        if(isset($_SESSION['log_data'][0]['type'])) {
          $profile_pic = $_SESSION['log_data'][0]['photo'];
        }
    }
    
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Uni</title>

  <!-- plugins:css -->
  <link rel="stylesheet" href="<?php echo $path; ?>/vendors/feather/feather.css">
  <link rel="stylesheet" href="<?php echo $path; ?>/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="<?php echo $path; ?>/vendors/css/vendor.bundle.base.css">
 
  <!-- endinject -->
  <!-- Plugin css for this page -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastify-js/1.6.1/toastify.css" integrity="sha512-VSD3lcSci0foeRFRHWdYX4FaLvec89irh5+QAGc00j5AOdow2r5MFPhoPEYBUQdyarXwbzyJEO7Iko7+PnPuBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  
  <link rel="stylesheet" href="<?php echo $path; ?>/vendors/datatables.net-bs4/dataTables.bootstrap4.css">
  <link rel="stylesheet" href="<?php echo $path; ?>/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" type="text/css" href="<?php echo $path; ?>/js/select.dataTables.min.css">
  <link rel="stylesheet" href="<?php echo $path; ?>/vendors/select2/select2.min.css">
  <link rel="stylesheet" href="<?php echo $path; ?>/vendors/select2-bootstrap-theme/select2-bootstrap.min.css">
  <link rel="stylesheet" href="<?Php echo $path;?>/css/main.css" type="text/css" >

  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="<?php echo $path; ?>/css/style.css">
  <script src="<?php echo $path; ?>/js/jquery-3.6.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastify-js/1.6.1/toastify.js" integrity="sha512-MnKz2SbnWiXJ/e0lSfSzjaz9JjJXQNb2iykcZkEY2WOzgJIWVqJBFIIPidlCjak0iTH2bt2u1fHQ4pvKvBYy6Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

  <!-- endinject -->
  
  <link rel="shortcut icon" href="<?php echo $path; ?>/images/logo.png" />
</head>
<body>
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
        <a class="navbar-brand brand-logo mr-5" href="<?php if(isset($_SESSION['log_data'][0]['club_id']) && isset($_SESSION['log_data'][0]['type']) == false){ echo '/dashboard_president'; }else if(isset($_SESSION['log_data'][0]['type'])){ echo '/member_workplace';}else{echo '/dashboard_admin';}?>"><img src="<?php echo $path;?>/images/logo.png" class="mr-2" alt="logo"/></a>
        <a class="navbar-brand brand-logo-mini" href="index.html"><img src="<?php echo $path;?>/images/logo_mini.png" alt="logo"/></a>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
          <span class="icon-menu"></span>
        </button>
        <ul class="navbar-nav mr-lg-2">
          <li class="nav-item nav-search d-none d-lg-block">
            <div class="input-group">
         
            </div>
          </li>
        </ul>
        <ul class="navbar-nav navbar-nav-right">
          <li class="nav-item dropdown">
           
            <?php if(isset($_SESSION['log_data'][0]['club_id'])){ require 'app/views/includes/notification_club.php'; }?>
          </li>
          <li class="nav-item nav-profile dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
                <?php if(isset($clubinfo) && $clubinfo != false){ ?>
                  <img src="/app/public/<?php if(isset($profile_pic)) echo $profile_pic; else echo $clubinfo[0]['logo']; ?>" alt="profile"/>
              <?php }?> 
            <?php if(isset($clubinfo) && $clubinfo != false){ ?>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
              <a class="dropdown-item" href="<?php if(isset($_SESSION['log_data'][0]['type']) == false) echo '/president/profile'; else echo '/member/profile' ?> ">
                <i class="ti-settings text-primary"></i>
                Réglages
              </a>
              <?php } ?>
              <a class="dropdown-item" href="/logout">
                <i class="ti-power-off text-primary"></i>
                Déconnecter
              </a>
            </div>
          </li>
          
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
          <span class="icon-menu"></span>
        </button>
      </div>
    </nav>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_settings-panel.html -->
    

   
      <!-- partial -->
      <!-- partial:partials/_sidebar.html -->
      <?php include('sidebar.php'); ?>
      <!-- partial -->
