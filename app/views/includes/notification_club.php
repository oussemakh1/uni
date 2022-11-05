    
    <?php
        use cgc\platform\controllers\NotificationController;    
        
        if(isset($_SESSION['log_data'][0]['club_id'])){


            $notification  = new NotificationController();

            $demande_notification = $notification->MyClubDemandeNotification($_SESSION['log_data'][0]['club_id']);
            $total_notification = $notification->MyClubTotalNotification($_SESSION['log_data'][0]['club_id']);

    ?>
     <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#" data-toggle="dropdown">
              <i class="icon-bell mx-0"></i>
              <span class="count text-primary"><p class="ml-2 font-weight-bold"><?php if(isset($_SESSION['log_data']['0']['type']) == false) echo $total_notification['total'];?></p></span>
            </a>
        <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
              <p class="mb-0 font-weight-normal float-left dropdown-header">Notifications</p>

              <?php
                if($demande_notification != false && isset($_SESSION['log_data']['0']['type']) == false): 
                foreach($demande_notification as $notification) { ?>
                    <a class="dropdown-item preview-item" href="/president/demande/preview/<?php echo $notification['demande_id']; ?>">
                        <div class="preview-thumbnail">
                        <div class="preview-icon <?php if($notification['status'] == 'accepted') echo 'bg-success'; else echo 'bg-danger';?>">
                            <i class="ti-info-alt mx-0"></i>
                        </div>
                        </div>
                        <div class="preview-item-content">
                        <h6 class="preview-subject font-weight-normal"><?php echo $notification['message'];?></h6>
                        <p class="font-weight-light small-text mb-0 text-muted">
                            <?php 
                                    $start_datetime = new DateTime($notification['created_at']); 
                                    $diff = $start_datetime->diff(new DateTime(date('Y/m/d h:i:s')));
                                    echo 'il y a '. $diff->h.' heure';
                            ?>
                        </p>
                        </div>
                    </a>
                <?php }  endif?>
            </div>
            <?php } ?>