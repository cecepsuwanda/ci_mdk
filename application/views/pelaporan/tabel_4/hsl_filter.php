 
 <?php
                       $pelaporan = new pelaporan;
                       
                       $subjudul=$pelaporan->getsubjudul($idkec,$iddesa,$iddusun,$idrt);
                       $html_txt = "<center><bold>JUMLAH PESERTA KB MENURUT KELOMPOK UMUR METODE KONTRASEPSI DAN JUMLAH ANAK</bold><br>$subjudul</center><br><br>";
                       
                       $tmp = $pelaporan->build_dt_tb($tmp_dt_tb);
                       $dt_tb = $tmp['dt_tb'];
                       $txt = $tmp['footer_tb'];
                      
                      $nmcontyp=$this->Contr_typ_model->getnm('');
                      $nmcontyp[]=array('1',array());
                      $nmcontyp[]=array('2',array());
                      $nmcontyp[]=array('3',array());
                      $nmcontyp[]=array('>3',array());
                      $numrows=$this->Contr_typ_model->getnumrows();

                      $tb_header = array('id'=>'tbhslfilter','width'=>'100%');

                     $tb_hslfilter = new mytable($tb_header,
                                array(
                       array(
                           array('NO. KODE',array('rowspan'=>'2')),
                                             array("KELOMPOK UMUR",array('rowspan'=>'2')),
                                             array('METODE KONTASEPSI',array('colspan'=>($numrows+1))),
                                             array('JUMLAH ANAK',array('colspan'=>'4'))
                                            ),
                                       $nmcontyp
                                 ),
                                $dt_tb,$txt);

                      $html_txt .= $tb_hslfilter->display('table table-bordered table-hover');
                      echo $html_txt;
 ?>              
         