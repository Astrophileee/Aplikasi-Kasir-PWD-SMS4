<?php include_once __DIR__.'/../layouts/session.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
<?php require_once __DIR__.'/../layouts/header.php'; ?>
</head>
<body>
<?php require_once __DIR__.'/../layouts/sidebar.php'; ?>
        <div class="main p-3">
            <div class="text-center">
                <h1>
                    KASIR
                </h1>
            </div>
            <div class="card col-sm-4 mb-5 card-cari">
                <div class="card-header " style="background-color: #DFF0D8;">
                    <h4><i class="fa fa-search"></i> Cari Barang</h4>
                </div>
                <div class="card-body">
                    <input type="text" id="cari" class="form-control" name="cari" placeholder="Masukan : Kode / Nama Barang">
                </div>
            </div>
            <div class="card col-sm-5 mt-5 card-hasil">
                <div class="card-header " style="background-color: #DFF0D8;">
                    <h4><i class="fa-solid fa-bars"></i> Hasil Barang</h4>
                </div>
                <div class="card-body" id="hasil">
                    <p class="text-muted">Cari barang</p>
                </div>
            </div>
            <div class="card col-sm-12 mt-5 card-keranjang">
                <div class="card-header " style="background-color: #DFF0D8;">
                    <h4><i class="fa fa-cart-shopping"></i> Keranjang</h4>
                </div>
                <div class="card-body">
                    <form action="">
                        <div class="mb-3">
                            <div class="input-group">
                                <?php
                                $tanggal = date("Y-m-d");
                                ?>
                                <span class="input-group-text" id="basic-addon3"><b>Tanggal Transaksi</b></span>
                                <input type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3 basic-addon4" value="<?= $tanggal ?>" disabled>
                            </div>
                        </div>
                        <table class="table" id="keranjangTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode barang</th>
                                    <th>Nama Barang</th>
                                    <th>QTY</th>
                                    <th>Total</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="total-bayar-label"><b>Total Bayar</b></span>
                            <input type="text" class="form-control" id="total-bayar" value="Rp 0" disabled>
                            <span class="input-group-text" id="bayar-label"><b>Bayar</b></span>
                            <input type="number" class="form-control" id="bayar">
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="kembalian-label"><b>Kembalian</b></span>
                            <input type="text" class="form-control" id="kembalian" value="Rp 0" disabled>
                        </div>
                        <button type="button" class="btn btn-primary mb-3" id="bayar-button">
                            <i class="fa fa-cart-shopping"></i> Bayar</button>
                    </form>
                </div>
            </div>
        </div>
<?php require_once __DIR__.'/../layouts/footer.php'; ?>
<script>$(document).ready(function(){
    function toggleBayarInput() {
    let totalBayar = calculateTotalBayar();
    if (totalBayar === 0) {
        $('#bayar').prop('disabled', true);
    } else {
        $('#bayar').prop('disabled', false);
    }
}

    function toggleBayarButton() {
    if (keranjang.length === 0) {
        $('#bayar-button').prop('disabled', true);
    } else {
        $('#bayar-button').prop('disabled', false);
    }
}
    let keranjang = [];
    let itemNumber = 1;

    function formatRupiah(angka, prefix) {
        var number_string = angka.toString().replace(/[^,\d]/g, ''),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix === undefined ? rupiah : (rupiah ? 'Rp ' + rupiah : '');
    }

    function parseRupiah(rupiah) {
        return parseInt(rupiah.replace(/[^0-9]/g, ''), 10);
    }

    function calculateTotalBayar() {
        let total = keranjang.reduce((sum, item) => sum + item.total, 0);
        $('#total-bayar').val(formatRupiah(total, 'Rp '));
        return total;
    }

    function updateKembalian() {
        let totalBayar = calculateTotalBayar();
        let bayar = parseRupiah($('#bayar').val()) || 0;
        let kembalian = bayar - totalBayar;
        $('#kembalian').val(formatRupiah(kembalian < 0 ? 0 : kembalian, 'Rp '));
        $('#bayar-button').prop('disabled', bayar < totalBayar);
    }

    function renderKeranjang() {
        let html = '';
        $.each(keranjang, function(index, item) {
            html += '<tr>';
            html += '<td>' + (index + 1) + '</td>';
            html += '<td>' + item.kode_barang + '</td>';
            html += '<td>' + item.nama_barang + '</td>';
            html += '<td><input style="width: 50px;" type="number" class="form-control qty-input" data-index="' + index + '" value="' + item.qty + '" min="1"></td>';
            html += '<td>' + formatRupiah(item.total, 'Rp ') + '</td>';
            html += '<td><button class="btn btn-danger btn-remove" data-index="' + index + '">Remove</button></td>';
            html += '</tr>';
        });
        $('#keranjangTable tbody').html(html);
        calculateTotalBayar();
        updateKembalian();
        toggleBayarButton();
        toggleBayarInput();
    }

    $('#cari').keyup(function(){
        var searchTerm = $(this).val();
        if (searchTerm.trim() === '') {
            $('#hasil').html('<p class="text-muted">Cari barang</p>');
            return;
        }
        $.ajax({
            url: 'searchBarang.php',
            type: 'post',
            data: {searchTerm: searchTerm},
            dataType: 'json',
            success: function(response){
                if (response.length === 0) {
                    $('#hasil').html('<p class="text-muted">Barang tidak ditemukan</p>');
                    return;
                }
                var html = '<table class="table"><thead><tr><th>Kode Barang</th><th>Nama Barang</th><th>Merk</th><th>Harga Jual</th><th>Aksi</th></tr></thead><tbody>';
                $.each(response, function(index, item){
                    html += '<tr>';
                    html += '<td>' + item.kode_barang + '</td>';
                    html += '<td>' + item.nama_barang + '</td>';
                    html += '<td>' + item.merk + '</td>';
                    html += '<td>' + formatRupiah(item.harga_jual, 'Rp ') + '</td>';
                    if (item.stok > 0) {
                        html += '<td><button class="btn btn-primary btn-add" data-item=\'' + JSON.stringify(item) + '\'><i class="fa-solid fa-cart-shopping"></i></button></td>';
                    } else {
                        html += '<td><b>STOK BARANG HABIS</b></td>';
                    }
                    html += '</tr>';
                });
                html += '</tbody></table>';
                $('#hasil').html(html);
            }
        });
    });

    $('#bayar-button').off('click').on('click', function() {
        let totalBayar = calculateTotalBayar();
        let bayar = parseRupiah($('#bayar').val()) || 0;

        let penjualanData = {
            kode_barang: [],
            jumlah: [],
            total: []
        };

        keranjang.forEach(item => {
            penjualanData.kode_barang.push(item.kode_barang);
            penjualanData.jumlah.push(item.qty);
            penjualanData.total.push(item.total);
        });

        $.ajax({
            url: 'insertPenjualan.php',
            type: 'post',
            data: { 
                kode_barang: penjualanData.kode_barang,
                jumlah: penjualanData.jumlah,
                total: penjualanData.total,
                total_bayar: totalBayar,
                bayar: bayar
            },
            success: function(response){
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Transaksi berhasil!',
                    showConfirmButton: false,
                    timer: 1500
                });

                $('#bayar').val('');

                $('#cari').val('');

                $('#hasil').html('<p class="text-muted">Cari barang</p>');

                keranjang = [];
                renderKeranjang();
            }, error: function(xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Transaksi gagal. ' + xhr.responseText,
                });
            }
        });
    });

    $(document).on('click', '.btn-add', function(){
        var item = $(this).data('item');
        item.harga_jual = parseInt(item.harga_jual, 10);
        var existingItemIndex = keranjang.findIndex(function(i) { return i.kode_barang === item.kode_barang; });
        if (existingItemIndex !== -1) {
            keranjang[existingItemIndex].qty++;
            keranjang[existingItemIndex].total = keranjang[existingItemIndex].harga_jual * keranjang[existingItemIndex].qty;
        } 
        else {
            item.qty = 1;
            item.total = item.harga_jual;
            keranjang.push(item);
        }

        renderKeranjang();
    });

    $(document).on('click', '.btn-remove', function(){
        var index = $(this).data('index');
        keranjang.splice(index, 1);
        renderKeranjang();
    });

    $(document).on('change', '.qty-input', function(){
        var index = $(this).data('index');
        var newQty = $(this).val();
        if (newQty < 1) {
            $(this).val(1);
            newQty = 1;
        }
        keranjang[index].qty = newQty;
        keranjang[index].total = keranjang[index].harga_jual * newQty;
        renderKeranjang();
    });

    $('#bayar').on('input', function() {
        updateKembalian();
    });

    renderKeranjang();
    toggleBayarButton();
    toggleBayarInput();
});


window.addEventListener('load', function () {
    let urlParams = new URLSearchParams(window.location.search)
    let success = urlParams.get('success')
    let message = urlParams.get('message')

    if (success && message) {
        if (success === 'true') {
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: message,
                showConfirmButton: false,
                timer: 1500,
            })
        } else {
            Swal.fire({
                title: 'Error!',
                text: message,
                icon: 'error',
            })
        }
        window.history.replaceState(
            {},
            document.title,
            window.location.pathname
        )
    }
})


</script>
</body>
</html>