<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class mycmb
{
   
   public function cmb_option($data,$default='',$selected='')
   {
      $html_txt='';
      if($default!='' and $selected==''){
        $txt_selected = $selected==''  ? "selected='selected'" : '';
        $html_txt.="<option value='0' $txt_selected >$default</option>";
      }
      if(!empty($data)){    
         foreach($data as $k=>$v)
         {
             if($k==$selected){             
               $html_txt ="<option value='$k' selected='selected' >$v</option>".$html_txt;
             }else{
               $html_txt.="<option value='$k'>$v</option>";
             }
         }
      }
      return $html_txt;
   }   

   public function display($id,$data,$default='',$selected='',$disable=false,$class='form-control select2')
   {
     
     $html_txt="<select id='$id' class='$class' ".($disable==false ? '' : 'disabled="disabled"')." style='width: 100%;'>";
     $html_txt.=$this->cmb_option($data,$default,$selected);     
     $html_txt.='</select>';
     return $html_txt;
   }   

}