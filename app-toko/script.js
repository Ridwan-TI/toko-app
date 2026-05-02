document.addEventListener('DOMContentLoaded', function () {

    let semuaData = [];

    async function ambilDataBarang() {
    try {
        const response = await fetch('http://localhost/PTM-final/api-toko/get-barang.php');

        const text = await response.text();
        console.log('GET Response:', text);

        const hasil = text ? JSON.parse(text) : {};

        if (hasil.status === 'success') {
            semuaData = hasil.data || [];
            tampilkanData(semuaData);
        } else {
            console.error('Response tidak sesuai:', hasil);
        }

    } catch (error) {
        console.error('Gagal mengambil data:', error);
    }
}

    function tampilkanData(data) {
        let barisHTML = '';

        data.forEach(barang => {
            barisHTML += `
                <tr class="text-center hover:bg-gray-50 transition">
                    <td class="px-6 py-3">${barang.ID}</td>
                    <td class="px-6 py-3">${barang.nama_barang}</td>
                    <td class="px-6 py-3">
                        Rp ${Number(barang.harga).toLocaleString('id-ID')}
                    </td>
                </tr>
            `;
        });

        document.getElementById('tabel-barang').innerHTML = barisHTML;
        document.getElementById('total-barang').textContent = data.length;
    }

    // SEARCH
    document.getElementById('search').addEventListener('input', function () {
        const keyword = this.value.toLowerCase();

        const hasilFilter = semuaData.filter(barang =>
            barang.nama_barang.toLowerCase().includes(keyword)
        );

        tampilkanData(hasilFilter);
    });

    // EVENT LISTENER TAMBAH BARANG
    document.getElementById('btn-simpan').addEventListener('click', async function () {
        const nama  = document.getElementById('input-nama').value.trim();
        const harga = document.getElementById('input-harga').value.trim();

        if (!nama || !harga) {
            alert('⚠️ Nama barang dan harga tidak boleh kosong!');
            return;
        }

        try {
           const response = await fetch('http://localhost/PTM-final/api-toko/tambah_barang.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ nama_barang: nama, harga: parseInt(harga) })
});

const text = await response.text();
console.log('POST Response:', text);

const hasil = text ? JSON.parse(text) : {};

if (hasil.status === 'success') {
    alert('✅ ' + hasil.message);
    document.getElementById('input-nama').value  = '';
    document.getElementById('input-harga').value = '';
    ambilDataBarang();
} else {
    alert('❌ ' + (hasil.message || 'Response tidak valid'));
}

        } catch (error) {
            console.error('Gagal mengirim data:', error);
            alert('❌ Terjadi kesalahan koneksi ke server.');
        }
    });

    ambilDataBarang();

    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('./sw.js')
            .then(reg => console.log('Service Worker berhasil:', reg.scope))
            .catch(err => console.log('Gagal:', err));
    }

});