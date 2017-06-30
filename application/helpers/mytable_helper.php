<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

 
 
 class mytable
 {
    private $tbstat_;
	private $header_;
	private $data_;
	private $footer_;
	
    
	function __construct($tbstat,$header,$data,$footer)
	{
       $this->tbstat_=$tbstat;
	   $this->header_=$header;
	   $this->data_=$data;
	   $this->footer_=$footer;
	}
	
	function display($class='display',$rowattr=array('id'=>'mainrow'))
	{
	  
	    $ci =& get_instance();
        $ci->load->helper('htmltable_helper');
	    $tbl = new htmltable(null, $class, 0, 0, 0,$this->tbstat_);
		
		if(!empty($this->header_))
		{
			foreach($this->header_ as $header1)
			{		  
				$tbl->addRow();
				foreach($header1 as $data){
				  if(is_array($data)){
					 $tbl->addCell($data[0], null, 'header',$data[1]);
				  }else{		 
					 $tbl->addCell($data, null, 'header');
				  } 
				}  
			}
		}
		if(!empty($this->data_))
		{		
		  foreach($this->data_ as $row)
		  {
		    
			if(isset($row['attr'])){
		       $row1=$row['data'];
		       $tbl->addRow(null,$row['attr']); 
            }else{
               $tbl->addRow(null,$rowattr);
               $row1=$row;
            }
            
	        foreach($row1 as $v){ 
			   $tbl->addCell($v[0], null, 'data',$v[1]);
	        }
		  }		
		}
		
		
		
	  if($this->footer_!=""){	
	    return $tbl->display($this->footer_);
      }else{
        return $tbl->display();
      }	  
	}
	
 
 }
 ?>