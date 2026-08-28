
function deleteConfirm(event) {
  if (event.target.closest(".suprimer")) {
    const ok = confirm("Are You Sure?!");

    if (!ok) {
      event.preventDefault();
    }
  }
}

document.addEventListener("click", deleteConfirm);
