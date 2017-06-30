 
 <?php
                       $pelaporan = new pelaporan;
                       
                       $subjudul=$pelaporan->getsubjudul($idkec,$iddesa,$iddusun,$idrt);
                       $html_txt = "<center><bold>JUMLAH PASANGAN USIA SUBUR (PUS) DAN PESERTA KB MENURUT METODE KONTRASEPSI</bold><br>$subjudul</center>";
    
                        $nmklm = $pelaporan->getnmklm($idkec,$iddesa,$iddusun,$idrt);
                                               
                        $nmcontyp = $this->Contr_typ_model->getnm('',0,1,1);
                        $numrows=$this->Contr_typ_model->getnumrows();

                         $dt = $this->Unit_detail_model;
                      $nm = $pelaporan->getfooternmklm($idkec,$iddesa,$iddusun,$idrt,$dt);
                        $tmp = $pelaporan->build_dt_tb($tmp_dt_tb,array($nm,'KEL. PRASEJAHTERA<br>% THD. TOTAL KEL.','KEL. SEJAHTERA I<br>% THD. TOTAL KEL.'));
                        $dt_tb = $tmp['dt_tb'];
                        $txt = $tmp['footer_tb']; 
                        
                        $nocol=$pelaporan->build_nocol(21); 
                       $tb_header = array('id'=>'tbhslfilter','width'=>'100%');
    
                     $tb_hslfilter = new mytable($tb_header,
                                array(
                                     array(
                                            array('NO',array('rowspan'=>'2')),
                                              array($nmklm,array('rowspan'=>'2')),
                                              array("JUMLAH PUS",array('rowspan'=>'2')),
                                              array("JUMLAH PESERTA KB MENURUT METODE KONTRASEPSI",array('colspan'=>(($numrows*2)+1))),
                                              array("% PREV KB TOTAL",array('rowspan'=>'2'))
                                            ),                                          
                                       $nmcontyp,
                                       $nocol,
                                     ),
                                $dt_tb,$txt);


                      $html_txt .= $tb_hslfilter->display('table table-bordered table-hover');
                      echo $html_txt;
 ?>              
         