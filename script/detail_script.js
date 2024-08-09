document.getElementById('menu-icon').addEventListener('click', function() {
    document.getElementById('sidebar').classList.toggle('hide');
    document.querySelector('.navbar').classList.toggle('fullwidth');
    document.querySelector('.main-content').classList.toggle('fullwidth');
});

//button hapus
const btn_delete = document.getElementById("btn_delete");
btn_delete.addEventListener("click", function (event) {
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
      title: "PERHATIAN!",
      text: "Anda yakin untuk menghapus data?",
      icon: "warning",
      iconColor: "oranye",
      showCancelButton: true,
      confirmButtonText: "Hapus",
      cancelButtonText: "Batal",
      reverseButtons: true,
    })
    .then((result) => {
      if (result.isConfirmed) {
        swalWithBootstrapButtons.fire({
          title: "Sukses",
          text: "Data berhasil dihapus!",
          icon: "success",
        });
      } else if (
        /* Read more about handling dismissals below */
        result.dismiss === Swal.DismissReason.cancel
      ) {
        swalWithBootstrapButtons.fire({
          title: "Cancel",
          text: "Batal menghapus data ini?",
          icon: "error",
        });
      }
    });
});