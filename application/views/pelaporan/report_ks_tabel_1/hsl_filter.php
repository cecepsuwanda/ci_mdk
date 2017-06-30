 
 <?php
                       $pelaporan = new pelaporan;
                       
                       $nmks=array('pra_s'=>'PRAS S','ks_1'=>'KS I','ks_2'=>'KS II','ks_3'=>'KS III','ks_3_p'=>'KS III+'); 

                        $subjudul=$pelaporan->getsubjudul($idkec,$iddesa,$iddusun,$idrt);
                        $html_txt = "<center><bold>STATUS KERJA KEPALA KELUARGA BERDASARKAN TAHAPAN KELUARGA $nmks[$ks]</bold><br>$subjudul</center>";
                        
                        $nmklm = $pelaporan->getnmklm($idkec,$iddesa,$iddusun,$idrt);

                       
                        $empmnt= $this->Empmntstat_model->getnm('Kd_emp!=6');
                        $numrows=$this->Empmntstat_model->getnumrows();
                        
                        $tmp = $pelaporan->build_dt_tb($tmp_dt_tb);
                        $dt_tb = $tmp['dt_tb'];
                        $txt = $tmp['footer_tb']; 
                        

                       $tb_header = array('id'=>'tbhslfilter','width'=>'100%');
    
                      $tb_hslfilter = new mytable($tb_header,
                                 array(
                                      array(
                                           array('NO. KODE',array('rowspan'=>'3')),
                                               array($nmklm,array('rowspan'=>'3')),
                                               array('JENIS PEKERJAAN',array('colspan'=>($numrows+2))),
                                               array('JUMLAH',array('rowspan'=>'3'))
                                             ),
                                        array(
                                             array('BEKERJA',array('colspan'=>($numrows+1))),
                                               array('TIDAK BEKERJA',array('rowspan'=>'2'))
                                             ),
                                        $empmnt),
                                $dt_tb,$txt);


                      $html_txt .= $tb_hslfilter->display('table table-bordered table-hover');
                      echo $html_txt;
 ?>              
         