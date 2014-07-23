<?php 
//D:\xamppNew\mysql\bin>mysql -u root -h localhost -p
	function useDatabase($database)
	{
		sqlGo("use ".$database);
	}
	
	function getSQLassoc($query)
	{
	$i = 0;
	$proses = sqlGo($query);
	while($SQLarray = mysql_fetch_assoc($proses))
	{
		$data[$i] = $SQLarray;
		$i = $i + 1;
	
	}
	return $data;
	}
	
	function getNumbSQL($query)
	{
	$proses = sqlGo($query);
	$numb = mysql_num_rows($proses);
	return $numb;
	}

	function getSQL($query)
	{
	$proses = sqlGo($query);
	$SQLarray = mysql_fetch_array($proses);
	return $SQLarray;
	}
	
	function showTable()
	{
		$data = getSQLassoc("show tables");
		//Tables_in_janmatour
		return $data;
	}
	
	function deskribeTable($tabel)
	{
		$data = getSQLassoc("describe $tabel");
		return $data;
	}
	
	function selectTable($tabel,$where="1=1",$by="",$limit="")
	{
		$data = getSQLassoc("select * from $tabel where 1=1 AND $where $by $limit");
		return $data;
	}
	
	
	
	function seGen($tabel,$where="1=1",$by="",$tipe="",$limit="")
	{
		$data = "select *".$tipe." from ".$tabel." where 1=1 AND ".$where." ".$by." ".$limit;
		return $data;
	}
	
	function reGen($table,$fields_And_Value_Array)
	{
		
		foreach($fields_And_Value_Array as $key => $val)
		{
			$field_comp = $field_comp." `".$key."`,";
			$value_comp = $value_comp." '".$val."',";
		}
		$field_comp = strMinOne($field_comp);
		$value_comp = strMinOne($value_comp);
		$data = "replace into ".$table." (".$field_comp.") VALUE (".$value_comp.")";
		return $data;
	}
	
	function upGen($table,$fields_And_Value_Array,$where="1=1")
	{
		
		foreach($fields_And_Value_Array as $key => $val)
		{
			$field_comp = $field_comp.$key." = '".$val."',";
		}
		$field_comp = strMinOne($field_comp);
		$data = "update ".$table." set ".$field_comp." WHERE ".$where;
		return $data;
	}
	
	
	function sqlGo($query)
	{
		$return = mysql_query($query);
		return $return;
	}

	
	function createTable()
	{
		useDatabase(database);
		$tables = showTable();
		$akhir = "";
		$hasil = "";
		$cekAda = "";
		
		foreach($tables as $valTa)
		{
			
			$table = $valTa['Tables_in_'.database];
			$struktur = "";
				$script = "CREATE TABLE IF NOT EXISTS ";
				$descTable = deskribeTable($table);
				foreach($descTable as $val)
				{
					$struktur = $struktur.'`'.$val['Field'].'` '.$val['Type'].' '.nul($val['Null']).' '.($val['Extra']).',';
					if($val['Key']=="PRI")
					{
						$priScript = ',PRIMARY KEY (`'.$val['Field'].'`)';
						$cekAda = 1;
					}
				}
				if($cekAda!=1) { $priScript=""; }
				$struktur = strMinOne($struktur);
				$hasil = $script.$table." (".$struktur.$priScript.");";
				$akhir = $akhir.$hasil;
	
		}
		
		return $akhir;
	}
	
	function nul($status)
	{
		if($status == "NO") { return "NOT NULL";}
		else { return "NULL"; } 
	}
	
	function strMinOne($str)
	{
		$panjang = strlen($str)-1;
		return substr($str,0,$panjang);
	}
	
	function dataDump()
	{
		useDatabase(database);
		$akhir = "";
		$tables = showTable();
		foreach($tables as $valTa)
		{
			$value="";
			$tabel = $valTa['Tables_in_'.database];
			$dataTabel = selectTable($tabel);
			foreach($dataTabel as $key => $val)
			{
				$field = "";
				$value = $value."(";
				foreach($val as $k => $v)
				{
					$field = $field."`".$k."`,";
					$value = $value."'".$v."',";
				}
				$value = strMinOne($value);
				$value = $value."),";
			}
			$hasil = "INSERT INTO ".$tabel;
			$hasil = $hasil."(".strMinOne($field).")";
			$hasil = $hasil." VALUE ".strMinOne($value).";";
			$akhir = $akhir.$hasil;
		}
		
		return $akhir;
	}
	
	function tglEng($tanggal)
	{
		$x = explode("-",$tanggal);
		$tahun = $x[0];
		$bulan = $x[1];
		$tgl = substr($x[2],0,2);
		return $bulan."/".$tgl."/".$tahun;
	}
	
?>
