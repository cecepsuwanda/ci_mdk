 
 <?php
                       $pelaporan = new pelaporan;
                       
                       $subjudul=$pelaporan->getsubjudul($idkec,$iddesa,$iddusun,$idrt);
                       $html_txt = "<center><bold>JUMLAH PUS DAN PUS PESERTA KB, PUS BUKAN PESERTA KB MENURUT KELOMPOK UMUR DAN ALASAN TIDAK BER-KB</bold><br>$subjudul</center><br><br>";
                       
                       $tmp = $pelaporan->build_dt_tb($tmp_dt_tb);
                       $dt_tb = $tmp['dt_tb'];
                       $txt = $tmp['footer_tb'];
                                                                
                      $nmnonacptr=$this->Non_acptr_reas_model->getnm('');  
                      $numrows=$this->Non_acptr_reas_model->getnumrows(); 

                      $tb_header = array('id'=>'tbhslfilter','width'=>'100%');

                     $tb_hslfilter = new mytable($tb_header,
                                 array(
                        array(
                           array('NO. KODE',array('rowspan'=>'2')),
                                               array("KELOMPOK UMUR",array('rowspan'=>'2')),
                                               array('JUMLAH PUS',array('rowspan'=>'2')),
                                               array('PUS PESERTA KB',array('rowspan'=>'2')),
                                               array('PUS BUKAN PESERTA KB',array('colspan'=>($numrows+1)))
                                        ),
                                        $nmnonacptr
                                      ),
                                 $dt_tb,$txt);

                      $html_txt .= $tb_hslfilter->display('table table-bordered table-hover');
                      echo $html_txt;
 ?>              
         