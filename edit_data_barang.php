<?php require_once('Connections/koneksi.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
	//Jika ada gambar baru diupload
	if($_FILES['gambar_barang']['name']<>'')
	{
		  $updateSQL = sprintf("UPDATE barang SET id_kategori=%s, nama_barang=%s, harga_barang=%s, stok_barang=%s, gambar_barang=%s WHERE id_barang=%s",
							   GetSQLValueString($_POST['id_kategori'], "int"),
							   GetSQLValueString($_POST['nama_barang'], "text"),
							   GetSQLValueString($_POST['harga_barang'], "int"),
							   GetSQLValueString($_POST['stok_barang'], "int"),
							   GetSQLValueString($_FILES['gambar_barang']['name'], "text"), //jgn lupa diganti yaaa
							   GetSQLValueString($_POST['id_barang'], "int"));
		
		  mysql_select_db($database_koneksi, $koneksi);
		  $Result1 = mysql_query($updateSQL, $koneksi) or die(mysql_error());
		  //Upload gambar barang ke folder gambar
			copy($_FILES['gambar_barang']['tmp_name'],"gambar/".$_FILES['gambar_barang']['name']);
	
	} else { //jika tidak ada gambar baru diupload
		$updateSQL = sprintf("UPDATE barang SET id_kategori=%s, nama_barang=%s, harga_barang=%s, stok_barang=%s WHERE id_barang=%s",
							   GetSQLValueString($_POST['id_kategori'], "int"),
							   GetSQLValueString($_POST['nama_barang'], "text"),
							   GetSQLValueString($_POST['harga_barang'], "int"),
							   GetSQLValueString($_POST['stok_barang'], "int"),
							   GetSQLValueString($_POST['id_barang'], "int"));
		
		  mysql_select_db($database_koneksi, $koneksi);
		  $Result1 = mysql_query($updateSQL, $koneksi) or die(mysql_error());
	}

  $updateGoTo = "list_data_barang.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
    ?>
  <script>
  alert("Data sukses tersimpan");
  document.location = "list_data_barang.php";
  </script>
  <?php
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_barang = "-1";
if (isset($_GET['id_barang'])) {
  $colname_barang = $_GET['id_barang'];
}
mysql_select_db($database_koneksi, $koneksi);
$query_barang = sprintf("SELECT * FROM barang WHERE id_barang = %s", GetSQLValueString($colname_barang, "int"));
$barang = mysql_query($query_barang, $koneksi) or die(mysql_error());
$row_barang = mysql_fetch_assoc($barang);
$totalRows_barang = mysql_num_rows($barang);

mysql_select_db($database_koneksi, $koneksi);
$query_kategori = "SELECT * FROM kategori_barang ORDER BY nama_kategori ASC";
$kategori = mysql_query($query_kategori, $koneksi) or die(mysql_error());
$row_kategori = mysql_fetch_assoc($kategori);
$totalRows_kategori = mysql_num_rows($kategori);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Edit Data Barang</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css" />

</head>

<body>
<h1>Form Edit Data Barang</h1>
<form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" name="form1" id="form1">
  <div class="form-group">
    <label for="exampleInputEmail1">Kategori Barang</label>
    <select name="id_kategori" class="form-control">
        <?php 
do {  
?>
        <option value="<?php echo $row_kategori['id_kategori']?>" <?php if (!(strcmp($row_kategori['id_kategori'], htmlentities($row_barang['id_kategori'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>><?php echo $row_kategori['nama_kategori']?></option>
        <?php
} while ($row_kategori = mysql_fetch_assoc($kategori));
?>
      </select>
  </div>

  <div class="form-group">
    <label for="exampleInputEmail1">Nama Barang</label>
    <input class="form-control" type="text" name="nama_barang" value="<?php echo htmlentities($row_barang['nama_barang'], ENT_COMPAT, 'utf-8'); ?>" size="32" />
  </div>
  <div class="form-group">
    <label for="exampleInputEmail1">Harga Barang</label>
   <input class="form-control" type="text" name="harga_barang" value="<?php echo htmlentities($row_barang['harga_barang'], ENT_COMPAT, 'utf-8'); ?>" size="32" />
  </div>
  <div class="form-group">
    <label for="exampleInputEmail1">Stok Barang</label>
    <input class="form-control" type="text" name="stok_barang" value="<?php echo htmlentities($row_barang['stok_barang'], ENT_COMPAT, 'utf-8'); ?>" size="32" />
  </div>
  <div class="form-group">
    <label for="exampleInputEmail1">Upload Gambar Barang</label>
     <img src="gambar/<?php echo $row_barang['gambar_barang']; ?>" width="100" /><br />
      <input class="form-control" type="file" name="gambar_barang" id="gambar_barang" />
  </div>
  <input type="hidden" name="MM_insert" value="form1" />

  <button type="submit" class="btn btn-primary">Simpan Data</button>

  <input type="hidden" name="id_barang" value="<?php echo $row_barang['id_barang']; ?>" />
  <input type="hidden" name="MM_update" value="form1" />
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($barang);

mysql_free_result($kategori);
?>
