<?php
include '../../functions/admin_auth.php';
include '../../config/database.php';

// Pastikan ada ID yang dikirim
if (!isset($_GET['id'])) {
    die("ID Pembayaran tidak ditemukan.");
}

$id_bayar = mysqli_real_escape_string($conn, $_GET['id']);

// Ambil detail pembayaran beserta nama penghuni dan kamar
$query = "SELECT pb.*, pd.nama_lengkap, k.nomor_kamar
          FROM pembayaran pb
          JOIN pendaftaran pd ON pb.id_pendaftar = pd.id
          LEFT JOIN kamar k ON pd.id_kamar = k.id
          WHERE pb.id = '$id_bayar' AND pb.status = 'verified'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    die("<div style='text-align:center; margin-top:50px; font-family:sans-serif;'>
            <h2>Data tidak ditemukan!</h2>
            <p>Pastikan pembayaran sudah divalidasi (Lunas) oleh Admin sebelum mencetak kwitansi.</p>
         </div>");
}

$data = mysqli_fetch_assoc($result);

// Format Nomor Kwitansi, misal: KWT/2026/02/0001
$no_kwitansi = "KWT/" . date('Y/m', strtotime($data['tanggal_bayar'])) . "/" . str_pad($data['id'], 4, "0", STR_PAD_LEFT);

// FUNGSI TERBILANG ANGKA KE HURUF
function terbilang($x) {
    $angka = ["", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas"];
    if ($x < 12) return " " . $angka[$x];
    elseif ($x < 20) return terbilang($x - 10) . " Belas";
    elseif ($x < 100) return terbilang($x / 10) . " Puluh" . terbilang($x % 10);
    elseif ($x < 200) return " Seratus" . terbilang($x - 100);
    elseif ($x < 1000) return terbilang($x / 100) . " Ratus" . terbilang($x % 100);
    elseif ($x < 2000) return " Seribu" . terbilang($x - 1000);
    elseif ($x < 1000000) return terbilang($x / 1000) . " Ribu" . terbilang($x % 1000);
    elseif ($x < 1000000000) return terbilang($x / 1000000) . " Juta" . terbilang($x % 1000000);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kwitansi Pembayaran - <?php echo $no_kwitansi; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body { background: #e9ecef; font-family: 'Arial', sans-serif; }
        
        .kwitansi-wrapper { 
            background: #fff; width: 850px; margin: 40px auto; padding: 50px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.1); position: relative; overflow: hidden;
            border: 1px solid #ddd;
        }
        
        /* Cap Lunas Background */
        .cap-lunas { 
            position: absolute; top: 35%; right: 10%; border: 6px solid rgba(231, 76, 60, 0.3); 
            color: rgba(231, 76, 60, 0.3); font-size: 80px; font-weight: 900; 
            padding: 10px 40px; border-radius: 15px; transform: rotate(-25deg); 
            letter-spacing: 10px; pointer-events: none;
        }
        
        .header-kop { border-bottom: 4px double #333; padding-bottom: 15px; margin-bottom: 30px; }
        .header-kop h2 { margin: 0; color: #2c3e50; font-weight: 900; letter-spacing: 1px; }
        .header-kop p { margin: 0; color: #555; font-size: 14px; }
        
        .title-area { text-align: center; margin-bottom: 40px; }
        .title-area h3 { font-weight: bold; text-decoration: underline; margin-bottom: 5px; }
        .title-area span { color: #666; font-size: 15px; }

        .table-kwitansi { width: 100%; font-size: 16px; margin-bottom: 40px; }
        .table-kwitansi td { padding: 12px 5px; vertical-align: top; }
        .table-kwitansi td:first-child { width: 220px; font-weight: bold; color: #333; }
        .table-kwitansi td:nth-child(2) { width: 30px; text-align: center; }
        .baris-isi { border-bottom: 1px dashed #aaa; font-weight: 500; text-transform: capitalize; }
        
        .box-terbilang { 
            background: #f8f9fa; border-radius: 5px; padding: 10px 15px; 
            font-style: italic; border-left: 5px solid #5A7863; font-weight: bold;
        }

        .footer-area { display: flex; justify-content: space-between; align-items: flex-end; }
        .total-box { 
            background: #5A7863; color: white; padding: 15px 40px; border-radius: 10px; 
            font-size: 28px; font-weight: 900; letter-spacing: 1px; box-shadow: inset 0 0 10px rgba(0,0,0,0.1);
        }
        .ttd-box { text-align: center; width: 250px; }
        .ttd-box p { margin-bottom: 80px; color: #333; }
        .ttd-box .nama-admin { font-weight: bold; text-decoration: underline; text-transform: uppercase;}

        @media print {
            body { background: #fff; margin: 0; padding: 0; }
            .kwitansi-wrapper { width: 100%; margin: 0; padding: 20px; box-shadow: none; border: none; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>

    <div class="text-center mt-4 mb-3 no-print">
        <button onclick="window.print()" class="btn btn-success btn-lg px-4 shadow-sm me-2">
            <i class="bi bi-printer me-2"></i>Cetak / Simpan PDF
        </button>
        <button onclick="window.close()" class="btn btn-secondary btn-lg px-4 shadow-sm">Tutup</button>
    </div>

    <div class="kwitansi-wrapper">
        <div class="cap-lunas">LUNAS</div>
        
        <div class="d-flex justify-content-between align-items-center header-kop">
            <div>
                <h2>ASRAMA MAHASISWA</h2>
                <p>Jl. Pendidikan No. 123, Kota Makassar, Sulawesi Selatan</p>
                <p>Telp: (0411) 123456 | Email: info@sima.com</p>
            </div>
            <div class="text-end">
                <h1 style="color: #5A7863; font-weight: 900; font-size: 45px; margin:0; line-height: 1;">SIMA</h1>
                <small style="color: #777; font-weight: bold; letter-spacing: 2px;">Manajemen Hunian</small>
            </div>
        </div>

        <div class="title-area">
            <h3>KWITANSI PEMBAYARAN</h3>
            <span>Nomor: <strong><?php echo $no_kwitansi; ?></strong></span>
        </div>

        <table class="table-kwitansi">
            <tr>
                <td>Telah Diterima Dari</td>
                <td>:</td>
                <td class="baris-isi fw-bold fs-5"><?php echo $data['nama_lengkap']; ?></td>
            </tr>
            <tr>
                <td>Nomor Kamar</td>
                <td>:</td>
                <td class="baris-isi"><?php echo $data['nomor_kamar'] ? 'Kamar ' . $data['nomor_kamar'] : '-'; ?></td>
            </tr>
            <tr>
                <td>Uang Sejumlah</td>
                <td>:</td>
                <td class="baris-isi">
                    <div class="box-terbilang"><?php echo trim(terbilang($data['jumlah_bayar'])) . " Rupiah"; ?></div>
                </td>
            </tr>
            <tr>
                <td>Untuk Pembayaran</td>
                <td>:</td>
                <td class="baris-isi">
                    <?php echo $data['keterangan']; ?> 
                    <br><small class="text-muted fst-italic text-lowercase">(Tanggal Transfer/Setor: <?php echo date('d F Y', strtotime($data['tanggal_bayar'])); ?>)</small>
                </td>
            </tr>
        </table>

        <div class="footer-area mt-5">
            <div class="total-box">
                Rp <?php echo number_format($data['jumlah_bayar'], 0, ',', '.'); ?>,-
            </div>
            
            <div class="ttd-box">
                <p>Makassar, <?php echo date('d F Y', strtotime($data['tanggal_bayar'])); ?><br>Penerima / Bendahara</p>
                <div class="nama-admin">( ........................................ )</div>
            </div>
        </div>
    </div>

</body>
</html>