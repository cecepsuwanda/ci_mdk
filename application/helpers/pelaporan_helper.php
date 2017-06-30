<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class pelaporan
{
    public function getsubjudul($idkec,$iddesa,$iddusun,$idrt)
	{
		$ci = & get_instance();
        $ci->load->model('Unit_detail_model');

		$subjudul='KAB/KOTA :KABUPATEN BANDUNG BARAT ';         
		$subjudul .= ($idkec>0) ? ' >> KECAMATAN : '.$ci->Unit_detail_model->getnm("id_unit_detail = '$idkec'") : '';        
		$subjudul .= ($iddesa>0) ? ' >> DESA : '.$ci->Unit_detail_model->getnm("id_unit_detail = '$iddesa'") : '';
		$subjudul .= ($iddusun>0) ? ' >> RW : '.$ci->Unit_detail_model->getnm("id_unit_detail = '$iddusun'") : '';   
		$subjudul .= ($idrt>0) ? ' >> RT : '.$ci->Unit_detail_model->getnm("id_unit_detail = '$idrt'") : '';         

		return $subjudul;  
	}

	public function build_dt_tb($tmp_dt_tb,$jdl_baris=array('JUMLAH'))
	{
		$dt_tb=array();
		$txt='';
		if(!empty($tmp_dt_tb))
		{
			
			foreach($tmp_dt_tb['isi_tb'] as $row)
			{
				$tmp=array();
				foreach ($row as $key => $value) {
					$tmp[]=array($value,array('align'=>'center'));            
				} 
				$dt_tb[]=$tmp;
			}
			
			$txt = ''; 
			$i=0;          
			foreach ($jdl_baris as $row) {
				$txt = $txt."<tr>";            
				$txt = $txt."<th colspan='2'>$row</th>";                      
				$jml1 = count($tmp_dt_tb['footer_tb']);
				$jml2 = count($tmp_dt_tb['footer_tb'],1);
				if($jml1==$jml2){
					foreach($tmp_dt_tb['footer_tb'] as $row)
					{
						$txt = $txt."<th align='right'>".$row."</th>";
					}
				}else{
					foreach($tmp_dt_tb['footer_tb'][$i] as $row)
					{
						$txt = $txt."<th align='right'>".$row."</th>";
					}
					$i++;
				}
				$txt = $txt."</tr>";
			}
		}
		

		return array('dt_tb'=>$dt_tb,'footer_tb'=>$txt);
	}

	public function dt_berulang($ulang,$data)
	{
		$tmp=array();
		for($i=1;$i<=$ulang;$i++){
           foreach($data as $row)
           {
           	 $tmp[]=array($row,array());
           } 
		}
		return $tmp;
	}

	public function getnmklm($idkec,$iddesa,$iddusun,$idrt)
	{
		$nmklm='KECAMATAN';
		$nmklm= $idkec ? 'DESA/KELURAHAN' : $nmklm;
		$nmklm= $iddesa ? 'DUSUN/RW' : $nmklm;
		$nmklm= $iddusun ? 'RT' : $nmklm;		
		return $nmklm;    
	}

	public function getfooternmklm($idkec,$iddesa,$iddusun,$idrt,$dt)
	{
		
		$nm = 'KABUPATEN BANDUNG BARAT';
		$nm = ($idkec!=0) ?  'KECAMATAN '.$dt->getnm("id_unit_detail='$idkec'") : $nm;
		$nm = ($iddesa!=0) ? 'DESA/KELURAHAN '.$dt->getnm("id_unit_detail='$iddesa'") : $nm;
		$nm = ($iddusun!=0) ? 'DUSUN/RW '.$dt->getnm("id_unit_detail='$iddusun'") : $nm;      
		
		return $nm;
	}

	public function build_nocol($jml)
	{
		$nocol=array();
		for($i=1;$i<=$jml;$i++)
		{
			$nocol[]=array($i,array());
		} 
		return $nocol;
	}

	




}