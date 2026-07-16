
function deleteConfirm(event) {
  if (event.target.closest(".suprimer")) {
    const ok = confirm("Êtes-vous sûr de vouloir supprimer cet utilisateur ?");

    if (!ok) {
      event.preventDefault();
    }
  }
}

document.addEventListener("click", deleteConfirm);
