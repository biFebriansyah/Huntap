<?php
/* ---------------------------------------------
 * SPK SAW
 * Author: Zunan Arif Rahmanto - 15111131
 * ------------------------------------------- */

/* ---------------------------------------------
 * Konek ke database & load fungsi-fungsi
 * ------------------------------------------- */
require_once('includes/init.php');

/* ---------------------------------------------
 * Load Header
 * ------------------------------------------- */
$judul_page = 'Perankingan Menggunakan Metode SAW';
require_once('template-parts/header.php');

/* ---------------------------------------------
 * Set jumlah digit di belakang koma
 * ------------------------------------------- */
$digit = 4;

/* ---------------------------------------------
 * Fetch semua kriteria
 * ------------------------------------------- */
$query = $pdo->prepare('SELECT id_kriteria, nama, type, bobot
	FROM kriteria ORDER BY urutan_order ASC');
$query->execute();
$query->setFetchMode(PDO::FETCH_ASSOC);
$kriterias = $query->fetchAll();

/* ---------------------------------------------
 * Fetch semua warga (alternatif)
 * ------------------------------------------- */
$query2 = $pdo->prepare('SELECT id_warga, nama_warga FROM warga');
$query2->execute();			
$query2->setFetchMode(PDO::FETCH_ASSOC);
$wargas = $query2->fetchAll();



$kriteria2 = query('SELECT * FROM kriteria ORDER BY urutan_order ASC');
$dataBobot = query('SELECT bobot FROM kriteria ORDER BY urutan_order ASC');
$nilai2 = [];
$nilaiA = [];
$niliaB = [];


/* >>> STEP 1 ===================================
 * Matrix Keputusan (X)
 * ------------------------------------------- */
$matriks_x = array();
global $nilai2;
$list_kriteria = array();
foreach($kriteria2 as $kriteria):
	$list_kriteria[$kriteria['id_kriteria']] = $kriteria;
	foreach($wargas as $warga):
		$id_warga = $warga['id_warga'];
		$id_kriteria = $kriteria['id_kriteria'];
		
		// Fetch nilai dari db
		$query3 = $pdo->prepare('SELECT nilai FROM nilai_warga
			WHERE id_warga = :id_warga AND id_kriteria = :id_kriteria');
		$query3->execute(array(
			'id_warga' => $id_warga,
			'id_kriteria' => $id_kriteria,
		));			
		$query3->setFetchMode(PDO::FETCH_ASSOC);
		$nilai_warga = $query3->fetch();
		if($nilai_warga) {
			// Jika ada nilai kriterianya
			$matriks_x[$id_kriteria][$id_warga] = $nilai_warga['nilai'];
			array_push($nilai2, $nilai_warga['nilai']);
		} else {
			$matriks_x[$id_kriteria][$id_warga] = 0;
		}

		// echo $matriks_x[$id_kriteria][$id_warga];

	endforeach;
endforeach;

/* >>> STEP 3 ===================================
 * Matriks Ternormalisasi (R)
 * ------------------------------------------- */
$matriks_r = array();
foreach($matriks_x as $id_kriteria => $nilai_wargas):
	
	$tipe = $list_kriteria[$id_kriteria]['type'];
	foreach($nilai_wargas as $id_alternatif => $nilai) {
		if($tipe == 'benefit') {
			$maxNilai = max($nilai_wargas);
			$minNilai = min($nilai_wargas);
			$nilai_a = $minNilai / $maxNilai;
			$nilai_b = $minNilai / $minNilai;
			// print_r($nilai_wargas);

		} elseif($tipe == 'cost') {
			$maxNilai = max($nilai_wargas);
			$minNilai = min($nilai_wargas);
			$nilai_a = $minNilai / $maxNilai;
			$nilai_b = $maxNilai / $maxNilai;

		}
		
		$matriks_r[$id_kriteria][$id_alternatif] = $nilai_normal;

	}
	
endforeach;


/* >>> STEP 4 ================================
 * Perangkingan
 * ------------------------------------------- */
$ranks = array();
foreach($wargas as $warga):

	$total_nilai = 0;
	foreach($list_kriteria as $kriteria) {
	
		$bobot = $kriteria['bobot'];
		$id_warga = $warga['id_warga'];
		$id_kriteria = $kriteria['id_kriteria'];
		
		$nilai_r = $matriks_r[$id_kriteria][$id_warga];
		$total_nilai = $total_nilai + ($bobot * $nilai_r);
	}
	
	$ranks[$warga['id_warga']]['id_warga'] = $warga['id_warga'];
	$ranks[$warga['id_warga']]['nama_warga'] = $warga['nama_warga'];
	$ranks[$warga['id_warga']]['nilai'] = $total_nilai;

	
endforeach;

?>

<?php

$angka = 0;
$userA = [];
$userB = [];
$resultA = [];
$resultB = [];

foreach($wargas as $warga):
	foreach($kriterias as $kriteria):
		$id_warga = $warga['id_warga'];
		$id_kriteria = $kriteria['id_kriteria'];
		$value = $matriks_x[$id_kriteria][$id_warga];
		if ($id_warga == 29) {
			array_push($userA, $value);
		} else {
			array_push($userB, $value);
		}
	endforeach;
endforeach;

if (count($userA) == count($userB)) {
	$angka = 0;

	foreach($kriteria2 as $data) :

		if ($data['type'] == "benefit") {
			$maxValue = max($userA[$angka], $userB[$angka]);
			$normalValue = min($userA[$angka], $userB[$angka]);
			$hasilA = $normalValue / $maxValue;
			$hasilB = $normalValue / $normalValue;

			array_push($resultA, $hasilA);
			array_push($resultB, $hasilB);

		}
		if ($data['type'] == "cost") {
			$minValue = min($userA[$angka], $userB[$angka]);
			$normalValue = max($userA[$angka], $userB[$angka]);
			$hasilA = $minValue / $normalValue;
			$hasilB = $normalValue / $normalValue;

			array_push($resultA, $hasilA);
			array_push($resultB, $hasilB);
		}

		$angka ++;
	endforeach;
}
?>


<?php
$finalA = 0;
$finalB = 0;
$hitungan = 0;
$final = [];

foreach ($kriteria2 as $data) :

	$bobot = $data['bobot'];
	$diKaliA = $bobot * $resultA[$hitungan];
	$diKaliB = $bobot * $resultB[$hitungan];
	$finalA = $finalA + $diKaliA;
	$finalB = $finalB + $diKaliB;
	
	$hitungan ++;
endforeach;
array_push($final, $finalA, $finalB);

?>

<div class="main-content-row">
<div class="container clearfix">	

	<div class="main-content main-content-full the-content">
		
		<h1><?php echo $judul_page; ?></h1>
		
		<!-- STEP 1. Matriks Keputusan(X) ==================== -->		
		<h3>Step 1: Matriks Keputusan (X)</h3>
		<table class="pure-table pure-table-striped">
			<thead>
				<tr class="super-top">
					<th rowspan="2" class="super-top-left">Nama Warga</th>
					<th colspan="<?php echo count($kriterias); ?>">Kriteria</th>
				</tr>
				<tr>
					<?php foreach($kriterias as $kriteria ): ?>
						<th><?php echo $kriteria['nama']; ?></th>
					<?php endforeach; ?>
				</tr>
			</thead>
			<tbody>
				<?php foreach($wargas as $warga): ?>
					<tr>
						<td><?php echo $warga['nama_warga']; ?></td>
						<?php						
						foreach($kriterias as $kriteria):
							$id_warga = $warga['id_warga'];
							$id_kriteria = $kriteria['id_kriteria'];
							echo '<td>';
							echo $matriks_x[$id_kriteria][$id_warga];
							echo '</td>';
						endforeach;
						?>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		
		<!-- STEP 2. Bobot Preferensi (W) ==================== -->
		<h3>Step 2: Bobot Preferensi (W)</h3>			
		<table class="pure-table pure-table-striped">
			<thead>
				<tr>
					<th>Kriteria</th>
					<th>Type</th>
					<th>Bobot (W)</th>						
				</tr>
			</thead>
			<tbody>
				<?php foreach($kriterias as $hasil): ?>
					<tr>
						<td><?php echo $hasil['nama']; ?></td>
						<td>
						<?php
						if($hasil['type'] == 'benefit') {
							echo 'Benefit';
						} elseif($hasil['type'] == 'cost') {
							echo 'Cost';
						}							
						?>
						</td>
						<td><?php echo $hasil['bobot']; ?></td>							
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		
		<!-- Step 3: Matriks Ternormalisasi (R) ==================== -->
		<h3>Step 3: Matriks Ternormalisasi (R)</h3>			
		<table class="pure-table pure-table-striped">
			<thead>
				<tr class="super-top">
					<th rowspan="2" class="super-top-left">Nama Warga</th>
					<th colspan="<?php echo count($kriterias); ?>">Kriteria</th>
				</tr>
				<tr>
					<?php foreach($kriterias as $kriteria ): ?>
						<th><?php echo $kriteria['nama']; ?></th>
					<?php endforeach; ?>
				</tr>
			</thead>
			<tbody>
				<?php foreach($wargas as $warga): ?>
					<tr>
						<?php
						$chekWarga = $warga['nama_warga'];
						$valueCheck = [];

						echo '<td>';
						echo $chekWarga;
						echo '</td>';

						if ($chekWarga == 'A') {
							$valueCheck = $resultA;
						}
						if ($chekWarga == 'B') {
							$valueCheck = $resultB;
						}

						foreach($valueCheck as $theData) :
							echo '<td>';
							echo $theData;
							echo '</td>';
						endforeach;

						?>
					</tr>
				<?php endforeach; ?>				
			</tbody>
		</table>		
		
		
		<!-- Step 4: Perangkingan ==================== -->
		<?php		
		$sorted_ranks = $ranks;		
		// Sorting
		if(function_exists('array_multisort')):
			$nama_warga = array();
			$nilai = array();
			foreach ($sorted_ranks as $key => $row) {
				$nama_warga[$key]  = $row['nama_warga'];
				$nilai[$key] = $row['nilai'];
			}
			array_multisort($nilai, $nama_warga, SORT_ASC, $sorted_ranks);
		endif;
		?>		
		<h3>Step 4: Perangkingan (V)</h3>			
		<table class="pure-table pure-table-striped">
			<thead>					
				<tr>
					<th class="super-top-left">Nama Warga</th>
					<th>Ranking</th>
				</tr>
			</thead>
			<tbody>
				<?php 
				$dg = 0;
				foreach($sorted_ranks as $warga ): ?>
					<tr>
						<td><?php echo $warga['nama_warga']; ?></td>
						<td><?php echo $final[$dg] ?></td>											
					</tr>
				<?php $dg ++; endforeach; ?>
			</tbody>
		</table>			
		
	</div>

</div><!-- .container -->
</div><!-- .main-content-row -->

<?php
require_once('template-parts/footer.php');