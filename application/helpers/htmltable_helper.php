<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// html_table.class.php from dyn-web.com
// version date: dec 2008 

class htmltable {
    
    private $rows = array();
    private $tableStr = '';
    
    function __construct($id = NULL, $klass = NULL, $border = 0, $cellspacing = 2, $cellpadding = 0, $attr_ar = array() ) {
        $this->tableStr = "\n<table" . ( !empty($id)? " id=\"$id\"": '' ) . 
            ( !empty($klass)? " class=\"$klass\"": '' ) . $this->addAttribs( $attr_ar ) . 
             " border=\"$border\" cellspacing=\"$cellspacing\" cellpadding=\"$cellpadding\">\n";
    }
    
    private function addAttribs( $attr_ar ) {
        $str = '';
		if(!empty($attr_ar)){	
			foreach( $attr_ar as $key=>$val ) {
				$str .= " $key=\"$val\"";
			}
		}	
        return $str;
    }
    
    public function addRow($klass = NULL, $attr_ar = array() ) {
        $row = new HTML_TableRow( $klass, $attr_ar );
        array_push( $this->rows, $row );
    }
    
    public function addCell($data = '', $klass = NULL, $type = 'data', $attr_ar = array() ) {
        $cell = new HTML_TableCell( $data, $klass, $type, $attr_ar );
        // add new cell to current row's list of cells
        $curRow = &$this->rows[ count( $this->rows ) - 1 ]; // copy by reference
        array_push( $curRow->cells, $cell );
    }
    
    public function display($foot="") {
        $this->tableStr .=" <thead>\n";
		foreach( $this->rows as $row ) {
     		$tmp = $this->getRowCells( $row->cells ,'th' );
		  if(!empty($tmp)){	
			$this->tableStr .= !empty($row->klass) ? "  <tr class=\"$row->klass\"": "  <tr";
            $this->tableStr .= $this->addAttribs( empty($row->attr_ar) ? null :$row->attr_ar ) . ">\n";
            $this->tableStr .= $tmp;
            $this->tableStr .= "  </tr>\n";
		  }	
		}
		$this->tableStr .=" </thead>\n";
		$this->tableStr .=" <tbody>\n";
		foreach( $this->rows as $row ) {
     		$tmp = $this->getRowCells( $row->cells ,'td' );
		  if(!empty($tmp)){	
			$this->tableStr .= !empty($row->klass) ? "  <tr class=\"$row->klass\"": "  <tr";
            $this->tableStr .= $this->addAttribs( empty($row->attr_ar) ? null :$row->attr_ar ) . ">\n";
            $this->tableStr .= $tmp;
            $this->tableStr .= "  </tr>\n";
		  }	
		}
		$this->tableStr .=" </tbody>\n";
        $this->tableStr .="<tfoot>\n";
		if(!empty($foot)){ 		 
		 $this->tableStr .= $foot;		 
        } 
 		$this->tableStr .= "</tfoot>\n";
        $this->tableStr .= "</table>\n";
        return $this->tableStr;
    }
    
    function getRowCells($cells,$tg) {
        $str = '';
        foreach( $cells as $cell ) {
            $tag = ($cell->type == 'data')? 'td': 'th';
          if($tg==$tag){  
            $str .= !empty($cell->klass) ? "    <$tag class=\"$cell->klass\"": "    <$tag";
            $str .= $this->addAttribs( empty($cell->attr_ar) ? null : $cell->attr_ar ) . ">";
            $str .= $cell->data;
            $str .= "</$tag>\n";
		  }	
        }
        return $str;
    }
    
}


class HTML_TableRow {
    function __construct($klass = NULL, $attr_ar = array()) {
        $this->klass = $klass;
        if(!empty($attr_ar)){
		 $this->attr_ar = $attr_ar;
        }
		$this->cells = array();
    }
}

class HTML_TableCell {
    function __construct( $data, $klass, $type, $attr_ar ) {
        $this->data = $data;
        $this->klass = $klass;
        $this->type = $type;
        if(!empty($attr_ar)){
		 $this->attr_ar = $attr_ar;
		} 
    }
}

?>