<?php 
function ruLogin()
{
	if($_SESSION['ID']=="")
	{
	redirect(page()."login"); exit();
	}
}

function seLogin()
{
if(strtotime(timeNow()) >= strtotime(timePlusPlus($_SESSION['last_activity'],0,0,1000,0,0,0)))
{

	redirect(page()."lock#time");
	exit();
}
}

function updateLogin()
{
	
	$data["last_activity"] = timeNow();
	$where = "id = '".$_SESSION['ID']."'";
	mysql_query(upGen("member",$data),$where);
	setPokes("url",url());
}


function cek()
{
ruLogin();	seLogin(); updateLogin();
}

function HakAkses($hakArray)
{
	$permision = 0;
	echo "<pre>";print_r($hakArray);echo "</pre>";
	foreach($hakArray as $kode)
	{
		
		if(3==$kode)
		{
			echo $permision = 1;
		}
	}
	if($permision == 0)
	{
		redirect(page()."page-not-found"); 
		exit();
	}
	
	return $CodeHakYangDizinkan;
}

function IDgenerate($namaDatabase)
{
	$now = timeNow();
	$tanggal = date("Y-m-d",strtotime($now));
	$dateID = str_replace("-","",$tanggal);
	$queryCariIDterbesar = "select right(id,4) as urutan from $namaDatabase where id like '%$dateID%' order by time_stamp desc";
	$status = getNumbSQL($queryCariIDterbesar);
	if($status > 0){
	$dataID = getSQL($queryCariIDterbesar);
	$urutanBaru = digit(($dataID['urutan'] + 1),4);
	$hasil = $dateID.$urutanBaru;
	} else {
	$hasil = $dateID."0001";
	}
	return $hasil;
}


function curldata($url){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

function specialCurl($url)
{
	$data = curldata($url);
	$step1 = explode("[systemcut]",$data);
	$step2 = explode("[sysdata]",$step1[1]);
	foreach($step2 as $vals)
	{
		$value = explode("[syssepa]",$vals);
		$key = $value[0];
		$nilai = $value[1];
		$hasil[$key] = $nilai;
	}
	return $hasil;
}

function AppTittel()
{
	$query = seGen("setting","setting.key = 'TITTLE' AND status = 'AKTIF'");
	$data = getSQL($query);
	return $data['value'];
}

function Menu($hakAkses)
{
	$query = seGen("menu","hakAkses like '%$hakAkses%' AND status = 'AKTIF'");
	$arrData = getSQLassoc($query);
	foreach($arrData as $val)
	{
		if($val['master_menu']=="true")
		{
			//Cari Sub Menunya
			$arrowbawah = '
			<span class="fa arrow"></span>
			';
			
			$querySubMenu = seGen("sub_menu","hakAkses like '%$hakAkse%' AND status = 'AKTIF' AND master_menu = '$val[id]'");
			$arrSub = getSQLassoc($querySubMenu);
			$submenu = '<ul style="height: 0px;" class="nav nav-second-level collapse">';
			foreach($arrSub as $valSub){
			$submenu = $submenu.'			
                                <li>
                                    <a href="'.$valSub['url'].'">'.$valSub['nama'].'</a>
                                </li>
			';
			}
			$submenu = $submenu.'</ul>';
		}
		else
		{
			$submenu = "";
			$arrowbawah = "";
		}
		$menu = $menu.'
		<li>
        <a href="'.$val['url'].'" '.$val['atribut'].'><i class="fa fa-'.$val['fafaCode'].' fa-fw"></i> '.$val['nama'].$arrowbawah.'</a>
        '.$submenu.'
        </li>';
	}
	return $menu;
}

function specialCurlServer($resArray)
{
echo "[systemcut]";
foreach($resArray as $key => $val)
{
	
	echo $key;
	echo "[syssepa]";
	echo $val;
	echo "[sysdata]";
}
echo "[systemcut]";	
}

function updateDrive($id_memeber,$sizeDrive)
{
	$quer = seGen("drive","id_member = '".$id_memeber."'","order by id desc limit 1");
	
	$dt = getSQL($quer);
	if($dt['drive']=="")
	{
		$drive = 0;
	}
	else
	{
		$drive = $dt['drive'];
	}
	
	$driveNow = $drive + $sizeDrive;
	$data = array(
	"id" => $dt['id'],
	"id_member" => $id_memeber,
	"drive" => $driveNow,
	"last_update" => timeNow(),
	"status" => "aktif",
	);
	$query = reGen("drive",$data);
	sqlGo($query);
}

function cekDisk()
{
	$query = seGen("drive","id_member = '".$_SESSION['user_id']."'");
	$data = getSQL($query);
	return $data['drive'];
}

function usedDisk()
{
	$query = seGen("used_quota","id_member = '".$_SESSION['user_id']."'");
	$data = getSQL($query);
	return $data['quota_now'];
}

function cekAva()
{
	$space = cekDisk();
	$ava = $space - usedDisk();
	return $ava;
}

function updateUseQuota($id_member,$filesize)
{
	$query = seGen("used_quota","id_member = '".$id_member."'");
	$data = getSQL($query);
	$quota_terpakai = $data['quota_now'];
	$quotasekarang = $filesize + $quota_terpakai;
	$data = array(
	"quota_now" => $quotasekarang
	);
	$queryUpdate = upGen("used_quota",$data,"id_member = '".$id_member."'");
	sqlGo($queryUpdate);
}

function cekExtensi($filename,$redirect)
{
	$file_parts = pathinfo($filename);
	$extensi = $file_parts['extension'];
	if($extensi=="pdf")
	{
		
	}
	else {
		redirect($redirect);exit();
	}
}

?>
