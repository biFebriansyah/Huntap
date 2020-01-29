<?php require_once('includes/init.php'); ?>
<?php cek_login($role = array(1, 2)); ?>

<?php
$ada_error = false;
$result = '';

$id_warga = (isset($_GET['id'])) ? trim($_GET['id']) : '';

if(!$id_warga) {
	$ada_error = 'Maaf, data tidak dapat diproses.';
} else {
	$query = $pdo->prepare('SELECT id_warga FROM kambing WHERE id_warga = :id_warga');
	$query->execute(array('id_warga' => $id_warga));
	$result = $query->fetch();
	
	if(empty($result)) {
		$ada_error = 'Maaf, data tidak dapat diproses.';
	} else {
		
		$handle = $pdo->prepare('DELETE FROM nilai_kambing WHERE id_warga = :id_warga');				
		$handle->execute(array(
			'id_warga' => $result['id_warga']
		));
		$handle = $pdo->prepare('DELETE FROM kambing WHERE id_warga = :id_warga');				
		$handle->execute(array(
			'id_warga' => $result['id_warga']
		));
		redirect_to('list-warga.php?status=sukses-hapus');
		
	}
}
?>

<?php
$judul_page = 'Hapus Warga';
require_once('template-parts/header.php');
?>

	<div class="main-content-row">
	<div class="container clearfix">
	
		<?php include_once('template-parts/sidebar-kambing.php'); ?>
	
		<div class="main-content the-content">
			<h1><?php echo $judul_page; ?></h1>
			
			<?php if($ada_error): ?>
			
				<?php echo '<p>'.$ada_error.'</p>'; ?>	
			
			<?php endif; ?>
			
		</div>
	
	</div><!-- .container -->
	</div><!-- .main-content-row -->


<?php
require_once('template-parts/footer.php');