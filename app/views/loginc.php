<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>UNI</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="app/views/vendors/feather/feather.css">
  <link rel="stylesheet" href="app/views/vendors/ti-icons/css/themify-icons.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <link rel="stylesheet" href="app/views/css/main.css"  />
  <link rel="stylesheet" href="app/views/vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastify-js/1.6.1/toastify.css" integrity="sha512-VSD3lcSci0foeRFRHWdYX4FaLvec89irh5+QAGc00j5AOdow2r5MFPhoPEYBUQdyarXwbzyJEO7Iko7+PnPuBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastify-js/1.6.1/toastify.js" integrity="sha512-MnKz2SbnWiXJ/e0lSfSzjaz9JjJXQNb2iykcZkEY2WOzgJIWVqJBFIIPidlCjak0iTH2bt2u1fHQ4pvKvBYy6Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

  <!-- endinject -->
  <!-- Plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="app/views/css/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="app/views/images/logo.png" />
</head>

<body>


<?php 

   require 'vendor/autoload.php';

   use cgc\platform\controllers\AuthController;
   use cgc\platform\libs\Redirect;

    $cred = [];
    
  
             

          
  

    if(isset($_POST['login']))
    {
        $email = $_POST['email'];
        
        $password = $_POST['password'];


        if(!empty($email) && !empty($password)) {
          
          $cred = [
            'email' => $email, 
            'password' => $password
          ];
          

          $auth = new AuthController();

          $tablename = "presidents";

          $validate = $auth->Login($cred, $tablename);
          
         

          if($validate !== false) {
            if(sizeof($validate) > 1) {
              $clubs = $validate;
            }
             else echo("<script>location.href='/dashboard_president'</script>");

          
          }

          //else echo("<script>location.href='/?status=error'</script>");;

        }

        if(isset($_COOKIE['club_id'])){
          if(sizeof($cred) > 1) {
            $club_id = json_decode($_COOKIE['club_id']);
            $log = $auth->ChooseClub('presidents',$cred,$club_id); 
            echo("<script>location.href='/dashboard_president'</script>");
    
          }
        }
    }
    

 

?>
 <?php if(isset($clubs) == true):  ?>
  <center>
 <div class="content-wrapper" >

          <div class="card col-md-6" style="margin-top:8rem">
            <div class="row">

              <?php foreach($clubs as $club): ?>
              <div  class="col-md-6 mb-4 mt-4">
                      <img  onclick="redirect(<?php echo $club['id']; ?>)" src="app/public/<?php echo $club['logo']; ?>" width="250px" class="drop-shadow-4  hover_card" />
                      <input  value="<?php echo $club['id']; ?>" hidden />

              </div>
              <?php endforeach; ?>
          
       
        </div>
   
 </div>
 </center>

 <?php endif; ?>

  <div class="container-scroller">

    <div class="container-fluid page-body-wrapper full-page-wrapper">
    
  
      <div class="content-wrapper d-flex align-items-center auth px-0">
    
      
        <div class="row w-100 mx-0">
          <div class="col-lg-4 mx-auto">
          
          <?php if(isset($_GET['status']) == 'error')
              { 
                 echo '<div class="error__toast"> <p>Email or password are wrong!</p> </div> '; 
              }
          ?>

            <div <?php if(isset($clubs)) echo 'hidden'; ?> class="auth-form-light text-left py-5 px-4 px-sm-5">
              <div class="brand-logo">
                <img src="app/views/images/escs.png" alt="logo">
              </div>

           
              <form class="pt-3" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
                
              <div class="form-group">
                  <input name="email" type="email" class="form-control form-control-lg " id="email" placeholder="Email">
                </div>
                <div class="form-group">
                  <input name="password" type="password" class="form-control form-control-lg" id="password" placeholder="Mot's de pass">
                </div>
                <div class="mt-3">
                  <button name="login" type="submit" id="login"  class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">LOG IN</button>
                </div>
          
              </form>
            </div>
    
      </div>


        </div>
      
      <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
 
  <script>

   
          

          function redirect(id){

         

               club_id = JSON.stringify(id);

               document.cookie = 'club_id ='+club_id;
                window.location.reload();
          }
        
    

  </script>

  <!-- container-scroller -->
  <!-- plugins:js -->
  <script src="app/views/vendors/js/vendor.bundle.base.js"></script>
  <!-- endinject -->
  <!-- Plugin js for this page -->
  <!-- End plugin js for this page -->
  <!-- inject:js -->
  <script src="app/views/js/off-canvas.js"></script>
  <script src="app/views/js/hoverable-collapse.js"></script>
  <script src="app/views/js/template.js"></script>
  <script src="app/views/js/settings.js"></script>
  <script src="app/views/js/todolist.js"></script>
  <!-- endinject -->
</body>

</html>
