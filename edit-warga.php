<?php require_once('includes/init.php'); ?>
<?php cek_login($role = array(1, 2)); ?>

<?php
$errors = array();
$sukses = false;

$ada_error = false;
$result = '';

$id_warga = (isset($_GET['id'])) ? trim($_GET['id']) : '';

if(!$id_warga) {
	$ada_error = 'Maaf, data tidak dapat diproses.';
} else {
	$query = $pdo->prepare('SELECT * FROM warga WHERE id_warga = :id_warga');
	$query->execute(array('id_warga' => $id_warga));
	$result = $query->fetch();
	
	if(empty($result)) {
		$ada_error = 'Maaf, data tidak dapat diproses.';
	}

	$id_warga = (isset($result['id_warga'])) ? trim($result['id_warga']) : '';
	$nama_warga = (isset($result['nama_warga'])) ? trim($result['nama_warga']) : '';
	$ciri_khas = (isset($result['ciri_khas'])) ? trim($result['ciri_khas']) : '';
	$tanggal_input = (isset($result['tanggal_input'])) ? trim($result['tanggal_input']) : '';
}

if(isset($_POST['submit'])):	
	
	$nama_warga = (isset($_POST['nama_warga'])) ? trim($_POST['nama_warga']) : '';
	$ciri_khas = (isset($_POST['ciri_khas'])) ? trim($_POST['ciri_khas']) : '';
	$tanggal_input = (isset($_POST['tanggal_input'])) ? trim($_POST['tanggal_input']) : '';
	$kriteria = (isset($_POST['kriteria'])) ? $_POST['kriteria'] : array();
	
	// Validasi ID warga
	if(!$id_warga) {
		$errors[] = 'ID warga tidak ada';
	}
	// Validasi
	if(!$nama_warga) {
		$errors[] = 'Nomor warga tidak boleh kosong';
	}
	if(!$tanggal_input) {
		$errors[] = 'Tanggal input tidak boleh kosong';
	}
	
	// Jika lolos validasi lakukan hal di bawah ini
	if(empty($errors)):
		
		$prepare_query = 'UPDATE warga SET nama_warga = :nama_warga, ciri_khas = :ciri_khas, tanggal_input = :tanggal_input WHERE id_warga = :id_warga';
		$data = array(
			'nama_warga' => $nama_warga,
			'ciri_khas' => $ciri_khas,
			'tanggal_input' => $tanggal_input,
			'id_warga' => $id_warga,
		);		
		$handle = $pdo->prepare($prepare_query);		
		$sukses = $handle->execute($data);
		
		if(!empty($kriteria)):
			foreach($kriteria as $id_kriteria => $nilai):
				$handle = $pdo->prepare('INSERT INTO nilai_warga (id_warga, id_kriteria, nilai) 
				VALUES (:id_warga, :id_kriteria, :nilai)
				ON DUPLICATE KEY UPDATE nilai = :nilai');
				$handle->execute( array(
					'id_warga' => $id_warga,
					'id_kriteria' => $id_kriteria,
					'nilai' =>$nilai
				) );
			endforeach;
		endif;
		
		redirect_to('list-warga.php?status=sukses-edit');
	
	endif;

endif;
?>

<?php
$judul_page = 'Edit Warga';
require_once('template-parts/header.php');
?>

	<div class="main-content-row">
	<div class="container clearfix">
	
		<?php include_once('template-parts/sidebar-warga.php'); ?>
	
		<div class="main-content the-content">
			<h1>Edit Warga</h1>
			
			<?php if(!empty($errors)): ?>
			
				<div class="msg-box warning-box">
					<p><strong>Error:</strong></p>
					<ul>
						<?php foreach($errors as $error): ?>
							<li><?php echo $error; ?></li>
						<?php endforeach; ?>
					</ul>
				</div>
				
			<?php endif; ?>
			
			<?php if($sukses): ?>
			
				<div class="msg-box">
					<p>Data berhasil disimpan</p>
				</div>	
				
			<?php elseif($ada_error): ?>
				
				<p><?php echo $ada_error; ?></p>
			
			<?php else: ?>				
				
				<form action="edit-warga.php?id=<?php echo $id_warga; ?>" method="post">
					<div class="field-wrap clearfix">					
						<label>Nama Warga <span class="red">*</span></label>
						<input type="text" name="nama_warga" value="<?php echo $nama_warga; ?>">
					</div>					
					<div class="field-wrap clearfix">					
						<label>No Kartu Keluarga / Identitas Lainnya</label>
						<textarea name="ciri_khas" cols="30" rows="2"><?php echo $ciri_khas; ?></textarea>
					</div>
					<div class="field-wrap clearfix">					
						<label>Tanggal Input <span class="red">*</span></label>
						<input type="text" name="tanggal_input" value="<?php echo $tanggal_input; ?>" class="datepicker">
					</div>	
					
					<h3>Nilai Kriteria</h3>
					<?php
					$query2 = $pdo->prepare('SELECT nilai_warga.nilai AS nilai, kriteria.nama AS nama, kriteria.id_kriteria AS id_kriteria, kriteria.ada_pilihan AS jenis_nilai 
					FROM kriteria LEFT JOIN nilai_warga 
					ON nilai_warga.id_kriteria = kriteria.id_kriteria 
					AND nilai_warga.id_warga = :id_warga 
					ORDER BY kriteria.urutan_order ASC');
					$query2->execute(array(
						'id_warga' => $id_warga
					));
					$query2->setFetchMode(PDO::FETCH_ASSOC);
					
					if($query2->rowCount() > 0):
					
						while($kriteria = $query2->fetch()):
						?>
							<div class="field-wrap clearfix">					
								<label><?php echo $kriteria['nama']; ?></label>
								<?php if(!$kriteria['jenis_nilai']): ?>
									<input type="number" step="0.001" name="kriteria[<?php echo $kriteria['id_kriteria']; ?>]" value="<?php echo ($kriteria['nilai']) ? $kriteria['nilai'] : 0; ?>">								
								<?php else: ?>
									<select name="kriteria[<?php echo $kriteria['id_kriteria']; ?>]">
										<option value="0">-- Pilih Variabel --</option>
										<?php
										$query3 = $pdo->prepare('SELECT * FROM pilihan_kriteria WHERE id_kriteria = :id_kriteria ORDER BY urutan_order ASC');			
										$query3->execute(array(
											'id_kriteria' => $kriteria['id_kriteria']
										));
										// menampilkan berupa nama field
										$query3->setFetchMode(PDO::FETCH_ASSOC);
										if($query3->rowCount() > 0): while($hasl = $query3->fetch()):
										?>
											<option value="<?php echo $hasl['nilai']; ?>" <?php selected($kriteria['nilai'], $hasl['nilai']); ?>><?php echo $hasl['nama']; ?></option>
										<?php
										endwhile; endif;
										?>
									</select>
								<?php endif; ?>
							</div>		
						<?php
						endwhile;
						
					else:					
						echo '<p>Kriteria masih kosong.</p>';						
					endif;
					?>
					
					<div class="field-wrap clearfix">
						<button type="submit" name="submit" value="submit" class="button">Update Data Warga</button>
					</div>
				</form>
				
			<?php endif; ?>			
			
		</div>
	
	</div><!-- .container -->
	</div><!-- .main-content-row -->


<?php
require_once('template-parts/footer.php');