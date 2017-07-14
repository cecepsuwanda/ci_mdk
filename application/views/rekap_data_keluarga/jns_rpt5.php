 
 <?php
                       $pelaporan = new pelaporan;
                       
                      $html_tab5="<center><bold>REKAPITULASI PESERTA KB PER MIX KONTRASEPSI HASIL PENDATAAN KELUARGA TINGKAT ".$pelaporan->getjudul($idkec,$iddesa,$iddusun,$idrt)."</bold><br><br></center>"; 
   
                        
                       $tmp = $pelaporan->build_dt_tb($tmp_dt_tb);
                       $dt_tb = $tmp['dt_tb'];
                       $txt = $tmp['footer_tb'];


                       $jdl=$pelaporan->getjdltab($idkec,$iddesa,$iddusun,$idrt);               
                       $tb_judul = new mytable(array('id'=>'tbjudul','width'=>'600px'),
                                                    array(),$jdl,'');    
                        
                       $html_tab5 .='<center>'.$tb_judul->display('').'</center><br><br>';
                       
                       $nocol = $pelaporan->build_nocol(14);

                              $nmklm = $pelaporan->getnmklm($idkec,$iddesa,$iddusun,$idrt);

                              $dt_contr = $this->Contr_typ_model;
                              $nmcontyp = array();
                              $nmcontyp = $dt_contr->getnm('',0,1,0,$nmcontyp,array('bgcolor'=>'#99CCFF'));
                              $numrows=$dt_contr->getnumrows();    
                           
                           $row_1 = array(array('NO. URUT',array('rowspan'=>'2','bgcolor'=>'#99CCFF')),
                                          array($nmklm,array('rowspan'=>'2','bgcolor'=>'#99CCFF')),
                                          array('STATUS KELUARGA KS I',array('rowspan'=>'2','bgcolor'=>'#99CCFF')),
                                          array('JUMLAH PUS',array('rowspan'=>'2','bgcolor'=>'#99CCFF')),
                                          array('PESERTA KB PER METODE KONTRASEPSI',array('colspan'=>$numrows+1,'bgcolor'=>'#99CCFF')),
                                          array('TINGKAT PREVALENSI KB ',array('rowspan'=>'2','bgcolor'=>'#99CCFF'))
                                        );       

                           $jdl_tb = array($row_1,                                          
                                           $nmcontyp,
                                           $nocol
                                          );
                           
                           $tb_hslfilter = new mytable(array('id'=>'tbtab5','width'=>'100%','border'=>'2','cellpadding'=>'8','cellspacing'=>'0','style'=>'border-collapse: collapse'),
                                                       $jdl_tb,
                                                       $dt_tb,$txt);
                           
                           $html_tab5 .= $tb_hslfilter->display('table table-bordered table-hover');

                        

                       echo $html_tab5;
 ?>              
         