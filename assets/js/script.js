// Konfirmasi sebelum menghapus: tambahkan class "form-hapus" pada <form>
document.querySelectorAll('form.form-hapus').forEach(function (form) {
    form.addEventListener('submit', function (e) {
        if (!confirm('Yakin ingin menghapus data ini? Tindakan tidak dapat dibatalkan.')) {
            e.preventDefault();
        }
    });
});

// Pratinjau foto sebelum diunggah: <input type="file" id="foto"> + <img id="preview">
var inputFoto = document.getElementById('foto');
if (inputFoto) {
    inputFoto.addEventListener('change', function () {
        var preview = document.getElementById('preview');
        if (this.files && this.files[0] && preview) {
            preview.src = URL.createObjectURL(this.files[0]);
            preview.classList.remove('d-none');
        }
    });
}
