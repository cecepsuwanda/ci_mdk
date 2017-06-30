 
 <?php
                       $pelaporan = new pelaporan;
                       
                       $subjudul=$pelaporan->getsubjudul($idkec,$iddesa,$iddusun,$idrt);
                       $html_txt = "<center><bold>JUMLAH JIWA USIA KERJA MENURUT KELOMPOK UMUR DAN JENIS PEKERJAAN</bold><br>$subjudul</center>";
                     
                        
                        $tmp = $pelaporan->build_dt_tb($tmp_dt_tb);
                        $dt_tb = $tmp['dt_tb'];
                        $txt = $tmp['footer_tb'];
                        
                        $empmnt = $this->Empmntstat_model->getnm('Kd_emp != 6');  
                        $numrows= $this->Empmntstat_model->getnumrows();

                        $tb_header = array('id'=>'tbhslfilter','width'=>'100%');

                        $tb_hslfilter = new mytable($tb_header,
                                                    array(
                                           array(
                                                array('NO. KODE',array('rowspan'=>'2')),
                                                                  array("KELOMPOK UMUR",array('rowspan'=>'2')),
                                                                  array('TIDAK BEKERJA',array('rowspan'=>'2')), 
                                                                  array('BEKERJA',array('colspan'=>($numrows+1))),
                                                                  array('JUMLAH',array('rowspan'=>'2'))),                                          
                                                           $empmnt
                                         ),
                                                   $dt_tb,$txt);


                      $html_txt .= $tb_hslfilter->display('table table-bordered table-hover');
                      echo $html_txt;
 ?>              
         