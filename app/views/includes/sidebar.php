
          <?php 

              /*------- PRESIDENT --------------*/
              if($current_user == 'president'):
          ?>

            <nav class="sidebar sidebar-offcanvas" id="sidebar">
              <ul class="nav">
                <li class="nav-item">
                  <a class="nav-link" href="/dashboard_president">
                    <i class="icon-grid menu-icon"></i>
                    <span class="menu-title">Tableau de bord</span>
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="/president/demande">
                    <i class="ti-file menu-icon"></i>
                    <span class="menu-title">Demands</span>
                  </a>
              
                </li>

                <li class="nav-item">
                  <a class="nav-link" data-toggle="collapse"  href="#ui-basic" aria-expanded="false" aria-controls="#ui-basic">
                    <i class="icon-head menu-icon"></i>
                    <span class="menu-title">Members</span>
                    <i class="menu-arrow"></i>
                  </a>
                  <div class="collapse" id="ui-basic">
                    <ul class="nav flex-column sub-menu">
                      <li class="nav-item"> <a class="nav-link" href="/president/members">List</a></li>
                      <li class="nav-item"> <a class="nav-link" href="/president/member/add">Ajout</a></li>
                    </ul>
                  </div>
                </li>

                <li class="nav-item">
                  <a class="nav-link" data-toggle="collapse"  href="#ui" aria-expanded="false" aria-controls="#ui">
                    <i class="ti-home menu-icon"></i>
                    <span class="menu-title">Départements</span>
                    <i class="menu-arrow"></i>
                  </a>
                  <div class="collapse" id="ui">
                    <ul class="nav flex-column sub-menu">
                      <li class="nav-item"> <a class="nav-link" href="/president/departements">List</a></li>
                      <li class="nav-item"> <a class="nav-link" href="/president/departement/add">Ajout</a></li>
                    </ul>
                  </div>
                </li>

                <li class="nav-item">
                  <a class="nav-link" data-toggle="collapse"  href="#ui-meeting" aria-expanded="false" aria-controls="#ui-meeting">
                    <i class="ti-calendar menu-icon"></i>
                    <span class="menu-title">Réunions</span>
                    <i class="menu-arrow"></i>
                  </a>
                  <div class="collapse" id="ui-meeting">
                    <ul class="nav flex-column sub-menu">
                      <li class="nav-item"> <a class="nav-link" href="/meetings/owned">List</a></li>
                      <li class="nav-item"> <a class="nav-link" href="/meetings/add">Ajout</a></li>
                    </ul>
                  </div>
                </li>

                
                <li class="nav-item">
                  <a class="nav-link" data-toggle="collapse"  href="#ui-task" aria-expanded="false" aria-controls="#ui-task">
                    <i class="ti-bag menu-icon"></i>
                    <span class="menu-title">Tâches</span>
                    <i class="menu-arrow"></i>
                  </a>
                  <div class="collapse" id="ui-task">
                    <ul class="nav flex-column sub-menu">
                      <li class="nav-item"> <a class="nav-link" href="/tasks/owned">List</a></li>
                      <li class="nav-item"> <a class="nav-link" href="/tasks/add">Ajout</a></li>
                    </ul>
                  </div>
                </li>
              
              </ul>
            </nav>

          <?php 
            
              /*------- END PRESIDENT --------------*/
              endif;


              /*------- MID MANAGEMENT --------------*/
              if($current_user == 'mid'):
          
          ?>
                 <nav class="sidebar sidebar-offcanvas" id="sidebar">
                      <ul class="nav">
                        <li class="nav-item">
                          <a class="nav-link" href="/dashboard_member">
                            <i class="icon-grid menu-icon"></i>
                            <span class="menu-title">Tableau de bord</span>
                          </a>
                        </li>
              
                        <li class="nav-item">
                          <a class="nav-link" data-toggle="collapse"  href="#ui-basic" aria-expanded="false" aria-controls="#ui-basic">
                            <i class="icon-head menu-icon"></i>
                            <span class="menu-title">Members</span>
                            <i class="menu-arrow"></i>
                          </a>
                          <div class="collapse" id="ui-basic">
                            <ul class="nav flex-column sub-menu">
                              <li class="nav-item"> <a class="nav-link" href="/team/members">List</a></li>
                            </ul>
                          </div>
                        </li>
              
                        <li class="nav-item">
                          <a class="nav-link" data-toggle="collapse"  href="#ui-meeting" aria-expanded="false" aria-controls="#ui-meeting">
                            <i class="ti-calendar menu-icon"></i>
                            <span class="menu-title">Réunions</span>
                            <i class="menu-arrow"></i>
                          </a>
                          <div class="collapse" id="ui-meeting">
                            <ul class="nav flex-column sub-menu">
                              <li class="nav-item"> <a class="nav-link" href="/meetings/owned">List</a></li>
                              <li class="nav-item"> <a class="nav-link" href="/meetings/add">Ajout</a></li>
                            </ul>
                          </div>
                        </li>
              
                        
                        <li class="nav-item">
                          <a class="nav-link" data-toggle="collapse"  href="#ui-task" aria-expanded="false" aria-controls="#ui-task">
                            <i class="ti-bag menu-icon"></i>
                            <span class="menu-title">Tâches</span>
                            <i class="menu-arrow"></i>
                          </a>
                          <div class="collapse" id="ui-task">
                            <ul class="nav flex-column sub-menu">
                              <li class="nav-item"> <a class="nav-link" href="/tasks/owned">List</a></li>
                              <li class="nav-item"> <a class="nav-link" href="/tasks/add">Ajout</a></li>
                            </ul>
                          </div>
                        </li>
                      
                      </ul>
                 </nav>

          <?php

           /*------- END MID MANAGEMENT --------------*/
             endif;


             /*------- MEMEBER --------------*/
             if($current_user == 'member'): 
          
          ?> 
               
               <nav class="sidebar sidebar-offcanvas" id="sidebar">

                  <li class="nav-item">
                    <a class="nav-link" href="/edit/club_preview?id=<?php echo $_SESSION['log_data'][0]['club_id']; ?>">
                      <i class="icon-grid menu-icon"></i>
                      <span class="menu-title">Info</span>
                    </a>
                  </li>
               </nav>
    
          <?php 

            /*------- END MEMBER --------------*/
             endif;

             /*------- ADMIN --------------*/
            if($current_user == 'admin'):

          ?> 
                
              <nav class="sidebar sidebar-offcanvas" id="sidebar">
                <ul class="nav">
                  <li class="nav-item">
                    <a class="nav-link" href="/dashboard_admin">
                      <i class="icon-grid menu-icon"></i>
                      <span class="menu-title">Tableau de bord</span>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="/admin/president/add">
                      <i class="icon-head menu-icon"></i>
                      <span class="menu-title">Presidents</span>
                    </a>
                  </li>
              
                  <li class="nav-item">
                    <a class="nav-link" data-toggle="collapse" href="#form-elements" aria-expanded="false" aria-controls="form-elements">
                      <i class="icon-columns menu-icon"></i>
                      <span class="menu-title">Clubs</span>
                      <i class="menu-arrow"></i>
                    </a>
                    <div class="collapse" id="form-elements" >
                      <ul class="nav flex-column sub-menu">
                        <li class="nav-item"><a class="nav-link" href="/admin/clubs">List</a></li>
                        <li class="nav-item"><a class="nav-link" href="/admin/club/add">Ajout</a></li>

                      </ul>
                    </div>
                  </li>
              
                </ul>
              </nav>
  
          <?php  endif; ?>


    

 
