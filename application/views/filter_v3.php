         <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Kecamatan</label>
                <?php
                   $mycmb = new mycmb;
                   //$data = array(1=>'Alaska',2=>'California',3=>'Delaware',4=>'Tennessee',5=>'Texas',6=>'Washington');
                   echo $mycmb->display('kec',$cmb_filter['kec'],'-- Semua Kecamatan --',$kec_select,$kec_select!='');
                   if($desa_select==''){
                ?>
                     <button type="submit" id='filter_kec' class="btn btn-info pull-right">Filter</button>
                 <?php
                    } 
                 ?>
              </div>
              <!-- /.form-group -->
              <div class="form-group">
                <label>Desa/Kelurahan</label>
                <?php 
                   echo $mycmb->display('desa',$cmb_filter['desa'],'-- Semua Desa/Kelurahan --',$desa_select,$desa_select!=''); 
                   if($dusun_select==''){  
                ?>                                
                <button type="submit" id='filter_desa' class="btn btn-info pull-right">Filter</button>
                <?php  } ?>
              </div>
              <!-- /.form-group -->
            </div>
            <!-- /.col -->
            <div class="col-md-6">
              <div class="form-group">
                <label>Dusun/RW</label>
                <?php 
                    echo $mycmb->display('dusun',$cmb_filter['dusun'],'-- Semua Dusun/RW --',$dusun_select,$dusun_select!=''); 
                    if($rt_select=='')
                    {
                ?>               
                <button type="submit" id='filter_dusun' class="btn btn-info pull-right">Filter</button>
                <?php
                    }
                ?>
              </div>
              <!-- /.form-group -->
              <div class="form-group">
                 <label>
                  <input type="radio" name="cmbks" id="cmbks" value="pra_s" class="minimal">
                  PRA S
                </label>
                <label>
                  <input type="radio" name="cmbks" id="cmbks" value="ks_1" class="minimal">
                  KS I
                </label>
                <label>
                  <input type="radio" name="cmbks" id="cmbks" value="ks_2" class="minimal">
                  KS II
                </label>
                <label>
                  <input type="radio" name="cmbks" id="cmbks" value="ks_3" class="minimal">
                  KS III
                </label>
                <label>
                  <input type="radio" name="cmbks" id="cmbks" value="ks_3_p" class="minimal">
                  KS III+
                </label>

              </div>
              <!-- /.form-group -->
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->