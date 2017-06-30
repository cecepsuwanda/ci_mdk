 
 <?php
                       $pelaporan = new pelaporan;
                       
                       $subjudul=$pelaporan->getsubjudul($idkec,$iddesa,$iddusun,$idrt);
                       $html_txt = "<center><bold>JUMLAH KEPALA KELUARGA MENURUT KELOMPOK UMUR STATUS TAHAPAN KELUARGA SEJAHTERA DAN PENDIDIKAN</bold><br>$subjudul</center>";
         
                      
                      $tmp = $pelaporan->build_dt_tb($tmp_dt_tb);
                      $dt_tb = $tmp['dt_tb'];
                      $txt = $tmp['footer_tb']; 
                      
                      $nmedulvl=$this->Edu_lvl_model->getnm('Kd_edu != 0');
                      $numrows=$this->Edu_lvl_model->getnumrows();

                        $tb_header = array('id'=>'tbhslfilter','width'=>'100%');

                      $tb_hslfilter = new mytable($tb_header,
                                array(
                       array(
                            array('NO. KODE',array('rowspan'=>'3')),
                                              array("KELOMPOK UMUR",array('rowspan'=>'3')),
                                              array('TAHAPAN KELUARGA SEJAHTERA',array('colspan'=>'5')),
                                              array('PENDIDIKAN',array('colspan'=>($numrows+2))),
                                              array('JUMLAH',array('rowspan'=>'3'))
                        ),                                          
                                       array( 
                            array('PRA S',array('rowspan'=>'2')), 
                                              array('KS I',array('rowspan'=>'2')),
                                              array('KS II',array('rowspan'=>'2')),
                                              array('KS III',array('rowspan'=>'2')),
                                              array('KS III+',array('rowspan'=>'2')),
                                              array('TIDAK SEKOLAH',array('rowspan'=>'2')),
                                              array('BERSEKOLAH',array('colspan'=>($numrows+1)))
                                            ),
                                       $nmedulvl  
                                    ),
                                $dt_tb,$txt);


                      $html_txt .= $tb_hslfilter->display('table table-bordered table-hover');
                      echo $html_txt;
 ?>              
         