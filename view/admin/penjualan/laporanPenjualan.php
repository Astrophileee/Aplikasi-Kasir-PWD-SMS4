<?php include_once __DIR__.'/../layouts/session.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once __DIR__.'/../layouts/header.php'; ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <?php require_once __DIR__.'/../layouts/sidebar.php'; ?>
    <div class="main p-3">
        <div class="text-center">
            <h1>LAPORAN PENJUALAN</h1>
        </div>
        <h4>Cari Laporan:</h4>
        <form id="filterForm">
            <div class="input-group mb-3">
                <div class="form-floating">
                    <select class="form-select" id="bulan" name="bulan">
                        <option value="0" selected>Pilih Bulan</option>
                        <option value="1">Januari</option>
                        <option value="2">Februari</option>
                        <option value="3">Maret</option>
                        <option value="4">April</option>
                        <option value="5">Mei</option>
                        <option value="6">Juni</option>
                        <option value="7">Juli</option>
                        <option value="8">Agustus</option>
                        <option value="9">September</option>
                        <option value="10">Oktober</option>
                        <option value="11">November</option>
                        <option value="12">Desember</option>
                    </select>
                    <label for="bulan">Bulan</label>
                </div>
                <div class="form-floating">
                    <select class="form-select" id="tahun" name="tahun">
                        <option selected value="0">Pilih Tahun</option>
                        <option value="2024">2024</option>
                        <option value="2023">2023</option>
                        <option value="2022">2022</option>
                        <option value="2021">2021</option>
                        <option value="2020">2020</option>
                    </select>
                    <label for="tahun">Tahun</label>
                </div>
            </div>
        </form>
        <h4>Cari Laporan Spesifik:</h4>
        <form id="specificDateForm">
            <div class="input-group mb-5">
                <div class="form-floating">
                    <input type="date" class="form-control" name="tanggal" id="tanggal">
                    <label for="tanggal">Tanggal Laporan</label>
                </div>
            </div>
        </form>
        <div id="result">
        </div>
    </div>
    <?php require_once __DIR__.'/../layouts/footer.php'; ?>
    <script>
        $(document).ready(function() {
            function loadData(params = {}) {
                $.ajax({
                    type: 'GET',
                    url: 'cariLaporan.php',
                    data: params,
                    success: function(response) {
                        $('#result').html(response);
                    }
                });
            }

            loadData();

            $('#bulan, #tahun').change(function() {
                const params = {
                    bulan: $('#bulan').val(),
                    tahun: $('#tahun').val()
                };
                loadData(params);
            });

            $('#tanggal').change(function() {
                const params = {
                    tanggal: $('#tanggal').val()
                };
                loadData(params);
            });
        });
    </script>
</body>

</html>
