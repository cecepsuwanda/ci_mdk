<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class mymenu
{
	private $mymenu_arr;
	private $myheader_arr;
	private $newmenu;
	private $idx_arry;

	function __construct()
	{
		$this->mymenu_arr=array();
		$this->myheader_arr=array();		
	}

    function new_header($id,$name)
    {
       $this->myheader_arr[] = array('id' => $id,'name'=>$name);
       return $this;
    }    

    function new_menu($id,$name,$link)
    {
       $this->newmenu = array('id' => $id,'name'=>$name,'link'=>$link,'isactive'=>'false');
       return $this;
    }

    private function cari_activesub(&$menus,$id,$len)
    {
          $tmp_id=substr($id, 0, $len);
          $tmp_subid = substr($id, $len+1,strlen($id));
          $idx=0;        
          foreach ($menus as $menu) {             
             if($menu['id']==$tmp_id)
             {          
                $menus[$idx]['isactive']='true';
                if(isset($menu['submenu']) and !empty($tmp_subid))
                 {
                   $this->cari_activesub($menus[$idx]['submenu'],$id,$len+2);
                  }

             }else{
                $menus[$idx]['isactive']='false';
             }  
            $idx++;
         } 
    }

    private function cari_active($id)
    {
      $tmp_id=substr($id, 0, 2);
      $tmp_subid = substr($id, 2,strlen($id));

      $idx=0;      
      foreach ($this->mymenu_arr as $menu) {
        if($menu['id']==$tmp_id)
        {          
          $this->mymenu_arr[$idx]['isactive']='true';
           if(isset($menu['submenu']) and !empty($tmp_subid))
           {                
                $this->cari_activesub($this->mymenu_arr[$idx]['submenu'],$id,4);
           }

        }else{
          $this->mymenu_arr[$idx]['isactive']='false';          
        }
        $idx++;
      }
    }

    function setactive($id=null)
    {
    	  
        if($id==null){         
         $this->newmenu['isactive']='true';         
        }else{
           $this->cari_active($id);
        }
        
        return $this;	
    }

    function setheader($id)
    {
    	$this->newmenu['header']=$id;
        return $this;	
    }    

    function add_righticon($icon)
    {
       $this->newmenu['right-icon']=$icon;
       return $this; 
    }

    function add_lefticon($icon,$text)
    {
      $this->newmenu['left-icon'][]=array('icon'=>$icon,'text'=>$text);
      return $this; 
    }

    function addmenutomainmenu()
    {
      $this->mymenu_arr[]=$this->newmenu;      	
    }

    private function addsubmenu($parentid,&$menus)
    {
          $idx=0;        
          foreach ($menus as $menu) {
             if($menu['id']==$parentid)
      	     {          
                $menus[$idx]['submenu'][]=$this->newmenu;
      	     }else{
      		     if(isset($menu['submenu']))
      		     {
                 $this->addsubmenu($parentid,$menus[$idx]['submenu']); 
      		     }
      	     }	
            $idx++;
         } 
    }
    
    function addmenuassubmenu($parentid)
    {
      $idx=0;      
      foreach ($this->mymenu_arr as $menu) {
      	if($menu['id']==$parentid)
      	{          
          $this->mymenu_arr[$idx]['submenu'][]=$this->newmenu;
      	}else{
      		if(isset($menu['submenu']))
      		{
                $this->addsubmenu($parentid,$this->mymenu_arr[$idx]['submenu']); 
      		}
      	}
      	$idx++;
      }
    }

    private function left_icon_disp($icons)
    {
        $html='<span class="pull-right-container">';
        foreach ($icons as $icon) {
           $html.='<small class="'.$icon['icon'].'">'.$icon['text'].'</small>';
        }
        $html.='</span>';
        return $html;
    }

    private function submenu_dis($menus)
    {
       $html='<ul class="treeview-menu">';
       foreach ($menus as $submenu) {
          if(isset($submenu['submenu'])){
                    $html.='<li class="'.($submenu['isactive']=='true' ? 'active ':'').'treeview">';
                    $html.='<a href="'.$submenu['link'].'">';
                    $html.='<i class="'.$submenu['right-icon'].'"></i> <span>'.$submenu['name'].'</span>';
                    $html.= isset($submenu['left-icon']) ? $this->left_icon_disp($submenu['left-icon']) :'';
                    $html.='</a>';
                    $html.=$this->submenu_dis($submenu['submenu']);
                    $html.='</li>';
          }else{
             $html.='<li'.($submenu['isactive']=='true' ? ' class="active" ':'').'>';
             $html.='<a href="'.$submenu['link'].'">';
             $html.='<i class="'.$submenu['right-icon'].'"></i>';
             $html.= isset($submenu['left-icon']) ? $this->left_icon_disp($submenu['left-icon']) :''; 
             $html.=$submenu['name'];
             $html.='</a>';
             $html.='</li>';
           }  
       }                      
       $html.='</ul>';
       return $html;
    }


    function display()
    {
      $html='';
	    foreach ($this->myheader_arr as $header) {
	    	$html.='<li class="header">'.$header['name'].'</li>';	    		
	    	foreach ($this->mymenu_arr as $menu) {
	    	   if($menu['header']==$header['id']){
		    	  if(isset($menu['submenu']))
		    	  {
		                    $html.='<li class="'.($menu['isactive']=='true' ? 'active ':'').'treeview">';
		                    $html.='<a href="'.$menu['link'].'">';
		                    $html.='<i class="'.$menu['right-icon'].'"></i> <span>'.$menu['name'].'</span>';
		                    $html.= isset($menu['left-icon']) ? $this->left_icon_disp($menu['left-icon']) :'';
		                    $html.='</a>';
		                    $html.=$this->submenu_dis($menu['submenu']);
		                    $html.='</li>';

		    	  }else{
		            $html.='<li class="'.($menu['isactive']=='true' ? 'active ':'').'">';
		            $html.='<a href="'.$menu['link'].'">';
		            $html.='<i class="'.$menu['right-icon'].'"></i> <span>'.$menu['name'].'</span>';
		            $html.= isset($menu['left-icon']) ? $this->left_icon_disp($menu['left-icon']) :'';          
		            $html.='</span>';
		            $html.='</a>';
		            $html.='</li>';
		    	  }
		    	}
	    	}
        }

    	return $html;
    }
}