<?php require_once('includes/init.php'); ?>
<?php cek_login($role = array(1, 2)); ?>

<?php
$errors = array();
$sukses = false;

$nama_warga = (isset($_POST['nama_warga'])) ? trim($_POST['nama_warga']) : '';
$ciri_khas = (isset($_POST['ciri_khas'])) ? trim($_POST['ciri_khas']) : '';
$kriteria = (isset($_POST['kriteria'])) ? $_POST['kriteria'] : array();


if(isset($_POST['submit'])):	
	
	// Validasi
	if(!$nama_warga) {
		$errors[] = 'Nama warga tidak boleh kosong';
	}	
	
	
	// Jika lolos validasi lakukan hal di bawah ini
	if(empty($errors)):
		
		$handle = $pdo->prepare('INSERT INTO warga (nama_warga, ciri_khas, tanggal_input) VALUES (:nama_warga, :ciri_khas, :tanggal_input)');
		$handle->execute( array(
			'nama_warga' => $nama_warga,
			'ciri_khas' => $ciri_khas,
			'tanggal_input' => date('Y-m-d')
		) );
		$sukses = "warga no. <strong>{$nama_warga}</strong> berhasil dimasukkan.";
		$id_warga = $pdo->lastInsertId();
		
		// Jika ada kriteria yang diinputkan:
		if(!empty($kriteria)):
			foreach($kriteria as $id_kriteria => $nilai):
				$handle = $pdo->prepare('INSERT INTO nilai_warga (id_warga, id_kriteria, nilai) VALUES (:id_warga, :id_kriteria, :nilai)');
				$handle->execute( array(
					'id_warga' => $id_warga,
					'id_kriteria' => $id_kriteria,
					'nilai' =>$nilai
				) );
			endforeach;
		endif;
		
		redirect_to('list-warga.php?status=sukses-baru');		
		
	endif;

endif;
?>

<?php
$judul_page = 'Tambah Warga';
require_once('template-parts/header.php');
?>

	<div class="main-content-row">
	<div class="container clearfix">
	
		<?php include_once('template-parts/sidebar-warga.php'); ?>
	
		<div class="main-content the-content">
			<h1>Tambah Warga</h1>
			
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
			
			
				<form action="tambah-warga.php" method="post">
					<div class="field-wrap clearfix">					
						<label>Nama Warga <span class="red">*</span></label>
						<input type="text" name="nama_warga" value="<?php echo $nama_warga; ?>">
					</div>					
					<div class="field-wrap clearfix">					
						<label>No. Kartu Keluarga / Identitas Lain</label>
						<textarea name="ciri_khas" cols="30" rows="2"><?php echo $ciri_khas; ?></textarea>
					</div>			
					
					<h3>Nilai Kriteria</h3>
					<?php
					$query = $pdo->prepare('SELECT id_kriteria, nama, ada_pilihan FROM kriteria ORDER BY urutan_order ASC');			
					$query->execute();
					// menampilkan berupa nama field
					$query->setFetchMode(PDO::FETCH_ASSOC);
					
					if($query->rowCount() > 0):
					
						while($kriteria = $query->fetch()):							
						?>
						
							<div class="field-wrap clearfix">					
								<label><?php echo $kriteria['nama']; ?></label>
								<?php if(!$kriteria['ada_pilihan']): ?>
									<input type="number" step="0.001" name="kriteria[<?php echo $kriteria['id_kriteria']; ?>]">								
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
											<option value="<?php echo $hasl['nilai']; ?>"><?php echo $hasl['nama']; ?></option>
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
						<button type="submit" name="submit" value="submit" class="button">Tambah Warga</button>
					</div>
				</form>
					
			
		</div>
	
	</div><!-- .container -->
	</div><!-- .main-content-row -->


<?php
require_once('template-parts/footer.php');