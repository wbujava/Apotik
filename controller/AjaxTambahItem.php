<?php 
Go("action");
function AjaxTambahItem()
{
	//CEK di Rec_pembelian
	$query = seGen("rec_pembelian","kasirID = '".$_SESSION['LoginID']."' AND status = 'TRANSAKSI'");
	$status = getNumbSQL($query);
	$data = getSQL($query);
	if($status > 0)
	{
		
		$id = $data['id']; //ID lama
	}
	else
	{
		$id = IDgenerate("rec_pembelian"); //ID BARU
	}

	//REPLACE DATA DI REC_PEMBELIAN
	$data = array(
	"id" => $id,
	"time_stamp" => timeNow(),
	"resep" => postAman('resep'),
	"ket" => postAman('keterangan'),
	"kasirID" => $_SESSION['LoginID'],
	"status" => "TRANSAKSI"
	);
	sqlGo(reGen("rec_pembelian",$data)); //REPLACE
	
	//MASUKIN CEK OBAT
	$queryObat = seGen("data_obat","BARCODE = '".postAman('kode_barang')."'");
	$dataObat = getSQL($queryObat);
	
	$data = array(
	"id" => IDgenerate("rec_pembelian_item"),
	"rec_pembelianID" => $id,
	"data_obatID" => $dataObat['id'],
	"jumlah" => postAman('jumlah_barang'),
	"harga" => $dataObat['harga'],
	"laba" => $dataObat['laba'],
	"status" => "TRANSAKSI",
	"time_stamp" => timeNow(),
	);
	sqlGo($queryTambah = reGen("rec_pembelian_item",$data));
	
	$tabel = '<tr>'; $no = 0;
	$queryTabel = "select *,sum(rec_pembelian_item.jumlah) as jumlah_total, sum(rec_pembelian_item.jumlah * rec_pembelian_item.harga) as harga_total from rec_pembelian,rec_pembelian_item,data_obat where rec_pembelian.id = rec_pembelianID AND data_obat.id = data_obatID AND kasirID = '".$_SESSION['LoginID']."' AND rec_pembelian_item.status = 'TRANSAKSI' group by data_obatID";
	$dataTabel = getSQLassoc($queryTabel);
	foreach($dataTabel as $key => $val)
	{
		$no++;
		$tabel = $tabel.'
		<td>'.$no.'</td>
		<td>'.$val['nama'].'</td>
		<td>'.$val['harga'].'</td>
		<td>'.$val['jumlah_total'].'</td>
		<td>'.$val['harga_total'] * $val['harga'].'</td>
		';
	}
	$tabel = $tabel.'
		</tr>
		';
	
	show($tabel);
}
?>
