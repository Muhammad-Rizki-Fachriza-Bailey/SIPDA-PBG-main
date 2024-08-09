document.getElementById('menu-icon').addEventListener('click', function() {
    document.getElementById('sidebar').classList.toggle('hide');
    document.querySelector('.navbar').classList.toggle('fullwidth');
    document.querySelector('.main-content').classList.toggle('fullwidth');
});

// button simpan start
const btn_save = document.getElementById("btn-save");
btn_save.addEventListener("click", function (event) {
  event.preventDefault(); 
  const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
      confirmButton: "btn btn-success",
      cancelButton: "btn btn-danger",
    },
    buttonsStyling: false,
  });
  swalWithBootstrapButtons
    .fire({
      title: "Simpan Perubahan?",
      text: "Pastikan data yang diubah telah benar dan sesuai dengan yang diinginkan!",
      icon: "question",
      iconColor: "green",
      showCancelButton: true,
      confirmButtonText: "Simpan!",
      cancelButtonText: "Batal!",
      reverseButtons: true,
    })
    .then((result) => {
      if (result.isConfirmed) {
        swalWithBootstrapButtons.fire({
          title: "Sukses",
          text: "Data berhasil diubah!",
          icon: "success",
        });
      } else if (
        /* Read more about handling dismissals below */
        result.dismiss === Swal.DismissReason.cancel
      ) {
        swalWithBootstrapButtons.fire({
          title: "Cancel",
          text: "Anda yakin membatalkan perubahan data?",
          icon: "error",
        });
      }
    });
});


