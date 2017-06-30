<?php
    $un=$this->session->userdata('un');
    $data = $this->User_model->get_login_info($un);    
?>

<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
     <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="<?php echo base_url();?>assets/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p> 
             <?php              
               echo $data['username'];
             ?>             
          </p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
            
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
        <?php 
            $mymenu = new mymenu;
            $mymenu->new_header('01','MENU'); 
            
            if(!empty($menu))
            {
               foreach ($menu as $row) {
                  $mymenu->new_menu($row['id'],$row['menu'],$row['link'])
                              ->add_righticon('fa fa-dashboard')
                              ->add_lefticon('fa fa-angle-left pull-right','')       
                              ->setheader('01');
                  if(in_array($row['id'], $menu_active)){ $mymenu->setactive(); }
                  $mymenu->addmenutomainmenu();
                 if(!empty($row['submenu'])){
                   foreach ($row['submenu'] as $row1) {
                      $mymenu->new_menu($row1['id'],$row1['menu'],$row1['link'])
                            ->add_righticon('fa fa-circle-o')
                            ->setheader('01');
                            if(in_array($row1['id'], $menu_active)){ $mymenu->setactive(); }
                      if(!empty($row1['submenu'])){
                          $mymenu->add_lefticon('fa fa-angle-left pull-right','')
                                 ->addmenuassubmenu($row1['id_induk']);
                          foreach ($row1['submenu'] as $row2) {
                            $mymenu->new_menu($row2['id'],$row2['menu'],$row2['link'])
                                   ->add_righticon('fa fa-circle-o')
                                   ->setheader('01');
                                   if(in_array($row2['id'], $menu_active)){ $mymenu->setactive(); }
                            if(!empty($row2['submenu'])){
                              $mymenu->add_lefticon('fa fa-angle-left pull-right','')
                                     ->addmenuassubmenu($row2['id_induk']);
                                foreach ($row2['submenu'] as $row3) {
                                        $mymenu->new_menu($row3['id'],$row3['menu'],$row3['link'])
                                               ->add_righticon('fa fa-circle-o')
                                               ->setheader('01');
                                        if(in_array($row3['id'], $menu_active)){ $mymenu->setactive(); }       
                                        $mymenu->addmenuassubmenu($row3['id_induk']); 
                                }
                            }else{         
                                   $mymenu->addmenuassubmenu($row2['id_induk']); 
                            } 
                          } 

                      }else{
                        $mymenu->addmenuassubmenu($row1['id_induk']);  
                      }                       
                   }
                 }


               }
            }
            echo $mymenu->display();
       ?>
      </ul>

    </section>
    <!-- /.sidebar -->
  </aside>