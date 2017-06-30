 
 <?php
                       $pelaporan = new pelaporan;
                       
                       $subjudul=$pelaporan->getsubjudul($idkec,$iddesa,$iddusun,$idrt);
                       $html_txt = "<center><bold>JUMLAH KEPALA KELUARGA PRASEJAHTERA MENURUT INDIKATOR YANG JATUH</bold><br>$subjudul</center>";
    
                      $nmklm = $pelaporan->getnmklm($idkec,$iddesa,$iddusun,$idrt);
                       
                      $nmkondks = $this->Indikator_ks_model->getnm('id_ind_ks_idk=1',0,0,1);
                      $numrows=$this->Indikator_ks_model->getnumrows()*2;

                      $nocols=$pelaporan->build_nocol(3+$numrows);    

                      $dt = $this->Unit_detail_model;
                      $nm = $pelaporan->getfooternmklm($idkec,$iddesa,$iddusun,$idrt,$dt);
                      $tmp = $pelaporan->build_dt_tb($tmp_dt_tb,array($nm));
                      $dt_tb = $tmp['dt_tb'];
                      $txt = $tmp['footer_tb']; 

                      $tb_header = array('id'=>'tbhslfilter','width'=>'100%');
    
                     $tb_hslfilter = new mytable($tb_header,
                                array(
                                     array(
                                            array('NO',array('rowspan'=>'2')),
                                              array($nmklm,array('rowspan'=>'2')),
                                              array("JUMLAH KELUARGA PRA SEJAHTERA",array('rowspan'=>'2')),
                                              array("JUMLAH KEPALA KELUARGA PRASEJAHTERA MENURUT INDIKATOR",array('colspan'=>($numrows*2)))
                                            ),                                          
                                       $nmkondks,
                                       $nocols,
                                     ),
                                $dt_tb,$txt);


                      $html_txt .= $tb_hslfilter->display('table table-bordered table-hover');
                      echo $html_txt;
 ?>              
         