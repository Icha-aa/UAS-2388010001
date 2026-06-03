document.addEventListener('DOMContentLoaded', function() {
    const tombol = document.getElementById('btn-interaktif');
    const pesan = document.getElementById('pesan-interaktif');

    if(tombol && pesan) {
        tombol.addEventListener('click', function() {
            pesan.textContent = "Halo! Terima kasih sudah mengunjungi CV saya. Semoga hari Anda menyenangkan! ✨";
            tombol.style.display = 'none'; // Sembunyikan tombol setelah diklik
        });
    }
});