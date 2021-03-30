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

mysql_select_db($database_koneksi, $koneksi);
$query_barang = "SELECT a.*, b.nama_kategori FROM barang as a, kategori_barang as b WHERE a.id_kategori = b.id_kategori ";
$barang = mysql_query($query_barang, $koneksi) or die(mysql_error());
$row_barang = mysql_fetch_assoc($barang);
$totalRows_barang = mysql_num_rows($barang);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>List Data Barang</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css" />
</head>

<body>
<h1>List Data Barang <a href="entry_data_barang.php" class="btn btn-success">Entry Data</a></h1>
<table id="example" class="table table-striped table-bordered" style="width:100%">
    <thead>  
        <tr>
            <th>ID Barang</th>
            <th>Nama Kategori</th>
            <th>Nama Barang</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Gambar</th>
            <th>Aksi</th>
          </tr>
          </thead>
        <tbody>
  <?php do { ?>
    <tr>
      <td height="27"><?php echo $row_barang['id_barang']; ?></td>
      <td><?php echo $row_barang['nama_kategori']; ?></td>
      <td><?php echo $row_barang['nama_barang']; ?></td>
      <td><?php echo $row_barang['harga_barang']; ?></td>
      <td><?php echo $row_barang['stok_barang']; ?></td>
      <td><img src="gambar/<?php echo $row_barang['gambar_barang']; ?>" width="50" /></td>
      <td><a href="edit_data_barang.php?id_barang=<?php echo $row_barang['id_barang']; ?>" class="btn btn-warning">Edit </a> <a href="#" onclick="hapus('<?php echo $row_barang['id_barang']; ?>')" class="btn btn-danger">Hapus</a></td>
    </tr>
    <?php } while ($row_barang = mysql_fetch_assoc($barang)); ?>
    </tbody>
</table>
</body>
</html>
<?php
mysql_free_result($barang);
?>
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
<script>
$(document).ready(function() {
    $('#example').DataTable();
} );
</script>
<script>
function hapus($id_barang)
{
	if(confirm("Yakin data ingin dihapus??"))
	{
		document.location = "hapus_data_barang.php?id_barang=" +$id_barang;
	}
}
</script>
