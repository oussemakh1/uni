<?php

require_once __DIR__.'/router.php';

$views_path =   '/app/views/pages/';
$views =  '/app/views/';
// ##################################################
// ##################################################
// ##################################################


// main page
get('/', 'home');




/*---------------------------- admin Routes -----------------------------*/

// login
get('/admin', $views.'login');
post('/admin', $views.'login');

// dashboard 
get('/dashboard_admin', $views_path.'admin/dashboard_admin');

// add club
get('/admin/club/add', $views_path.'club/club_insert');
post('/admin/club/add', $views_path.'club/club_insert');

// update club 
get('/admin/club/update/$id', $views_path.'club/club_update');
post('/admin/club/update/$id', $views_path.'club/club_update');


// edit club 
get('/admin/club/preview/$id', $views_path.'club/club_preview');



// list clubs
get('/admin/clubs', $views_path.'club/clubs');

// add president
get('/admin/president/add', $views_path.'president/president_add');
post('/admin/president/add', $views_path.'president/president_add');


// update president
get('/admin/presdient/update/$id', $views_path.'president/president_update');
post('/admin/presdient/update/$id', $views_path.'president/president_update');


// handle demande
get('/admin/demande/$id', $views_path.'demande/handle_demande');
post('/admin/demande/$id', $views_path.'demande/handle_demande');


/*---------------------------- Member Routes -----------------------------*/

// login
get('/login', $views.'loginm');
post('/login', $views.'loginm');

// dashboard 
get('/dashboard_member', $views_path.'member/dashboard_member');


// list members
get('/team/members', $views_path.'member/members');

// profile
get('/member/profile', $views_path.'member/profile');
post('/member/profile', $views_path.'member/profile');

/*---------------------------- President Routes -----------------------------*/

// login
get('/president', $views.'loginc');
post('/president', $views.'loginc');

// dashboard 
get('/dashboard_president', $views_path.'president/dashboard_president');

// profile
get('/president/profile', $views_path.'president/profile');
post('/president/profile', $views_path.'president/profile');

// send demande
get('/president/demande', $views_path.'demande/demande');
post('/president/demande', $views_path.'demande/demande');


// edit demande 
get('/president/demande/preview/$id', $views_path.'demande/preview');
post('/president/demande/preview/$id', $views_path.'demande/preview');

/******  Members  ********/

    // list members
    get('/president/members', $views_path.'member/members');

    // add member
    get('/president/member/add', $views_path.'member/member_add');
    post('/president/member/add', $views_path.'member/member_add');

    // update member 
    get('/president/member/update/$id', $views_path.'member/member_update');
    post('/president/member/update/$id', $views_path.'member/member_update');

    // edit member 
    get('/members/preview/$id', $views_path.'member/member_preview');



/****  Departments ******/

    // index departments
    get('/president/departements', $views_path.'department/departments');

    // add department
    get('/president/departement/add', $views_path.'department/department_add');
    post('/president/departement/add', $views_path.'department/department_add');

    // update department
    get('/president/department/update/$id', $views_path.'department/department_update');
    post('/president/department/update/$id', $views_path.'department/department_update');
    // edit department 
    get('/president/department/edit/$id', $views_path.'department/department_preview');



/*---------------------------- Meetings Routes -----------------------------*/

// add meeting
get('/meetings/add', $views_path.'meeting/meeting_add');
post('/meetings/add', $views_path.'meeting/meeting_add');

// meeting absence
get('/meetings/absence/$id', $views_path.'meeting/absence');
post('/meetings/absence/$id', $views_path.'meeting/absence');

// preview meeting
get('/meetings/preview/$id', $views_path.'meeting/meeting_preview');

// owned meetings
get('/meetings/owned', $views_path.'meeting/owned_meetings');




/*---------------------------- Tasks Routes -----------------------------*/

// add task
get('/tasks/add', $views_path.'task/task_add');
post('/tasks/add', $views_path.'task/task_add');

// task status
get('/tasks/status/$id', $views_path.'task/status');
post('/tasks/status/$id', $views_path.'task/status');

// preview task
get('/tasks/preview/$id', $views_path.'task/task_preview');

// owned tasks
get('/tasks/owned', $views_path.'task/owned_tasks');


// A route with a callback
get('/logout', function(){
  
  // destroy session
  session_start();
  
  session_unset();
  
  session_destroy();
  
  // remove cookies

  if (isset($_COOKIE['club_id'])) {
      unset($_COOKIE['club_id']); 
      setcookie('club_id', null, -1, '/'); 
      echo "    document.cookie = club_id =; Max-Age=-99999999;';  
      ";
   
  }
  
  header("location:/");
});


// For GET or POST
// The 404 which is inside the views folder will be called
// The 404 has access to $_GET and $_POST
any('/404','views/404');
