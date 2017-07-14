 
 <?php
                       $pelaporan = new pelaporan;
                       
                      $html_tab3="<center><bold>REKAPITULASI PESERTA KB PER MIX KONTRASEPSI HASIL PENDATAAN KELUARGA TINGKAT ".$pelaporan->getjudul($idkec,$iddesa,$iddusun,$idrt)."</bold><br><br></center>"; 
                         
                         
                        
                                     $txt = '<tr>';
                                     $txt .= "<th colspan='2'>JUMLAH</th>";
                                     if(isset($tmp_dt_tb['footer_tb']['row1'])){ 
                                                    
                                     foreach ($tmp_dt_tb['footer_tb']['row1'] as $key => $value) {                   
                                          $txt .= "<th align='center'>$value</th>";                  
                                      }
                                     }

                                     $txt .= '</tr>';
                                     $txt .= '<tr>';
                                     $txt .= "<th colspan='13'><B>KELUARGA PRA SEJAHTERA DAN ANGGOTA KELUARGA</B></th>";
                                     $txt .= '</tr>';
                                     $txt .= '<tr>';
                                     $txt .= "<th colspan='2'>JUMLAH</th>";
                                     if(isset($tmp_dt_tb['footer_tb']['row2'])){                
                                    
                                     foreach ($tmp_dt_tb['footer_tb']['row2'] as $key => $value) {
                                           $txt .= "<th align='center'>$value</th>"; 
                                      }
                                     }                            
                                     $txt .= '</tr>';
                                     $txt .= '<tr>';
                                     $txt .= "<th colspan='13'><B>KELUARGA SEJAHTERA I DAN ANGGOTA KELUARGA</B></th>";
                                     $txt .= '</tr>';
                                     $txt .= '<tr>';
                                     $txt .= "<th colspan='2'>JUMLAH</th>";
                                     if(isset($tmp_dt_tb['footer_tb']['row3'])){                
                                     
                                     foreach ($tmp_dt_tb['footer_tb']['row3'] as $key => $value) {
                                         
                                          $txt .= "<th align='center'>$value</th>";
                                        
                                      }
                                     }
                                     $txt .= '</tr>';

                         $tmp_dt_tb['footer_tb']=array();

                         $tmp = $pelaporan->build_dt_tb($tmp_dt_tb);
                         $dt_tb = $tmp['dt_tb'];
                         //$txt = $tmp['footer_tb'];


                         $jdl=$pelaporan->getjdltab($idkec,$iddesa,$iddusun,$idrt);               
                         $tb_judul = new mytable(array('id'=>'tbjudul','width'=>'600px'),
                                                      array(),$jdl,'');    
                          
                         $html_tab3 .='<center>'.$tb_judul->display('').'</center><br><br>';
                         
                         $nocol = $pelaporan->build_nocol(13);

                                $nmklm = $pelaporan->getnmklm($idkec,$iddesa,$iddusun,$idrt);

                                $nmcontyp = array();
                                $nmcontyp = $this->Contr_typ_model->getnm('',0,1,0,$nmcontyp,array('bgcolor'=>'#99CCFF'));
                                $numrows=$this->Contr_typ_model->getnumrows();    
                             
                             $row_1 = array(array('NO. URUT',array('rowspan'=>'2','bgcolor'=>'#99CCFF')),
                                            array($nmklm,array('rowspan'=>'2','bgcolor'=>'#99CCFF')),
                                            array('JUMLAH PUS',array('rowspan'=>'2','bgcolor'=>'#99CCFF')),
                                            array('PESERTA KB PER METODE KONTRASEPSI',array('colspan'=>$numrows+1,'bgcolor'=>'#99CCFF')),
                                            array('TINGKAT PREVALENSI KB ',array('rowspan'=>'2','bgcolor'=>'#99CCFF'))
                                          );       

                             $jdl_tb = array($row_1,                                          
                                             $nmcontyp,
                                             $nocol
                                            );
                             
                             $tb_hslfilter = new mytable(array('id'=>'tbtab3','width'=>'100%','border'=>'2','cellpadding'=>'8','cellspacing'=>'0','style'=>'border-collapse: collapse'),
                                                         $jdl_tb,
                                                         $dt_tb,$txt);
                             
                             $html_tab3 .= $tb_hslfilter->display('table table-bordered table-hover');

                         echo $html_tab3; 
 ?>              
         