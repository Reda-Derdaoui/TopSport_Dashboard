const id2 = document.getElementById("id2");
const dateDEbut = document.getElementById("dateDEbut");
const prix = document.getElementById("prix");

const editButtons2 = document.querySelectorAll(".editBtn2");

editButtons2.forEach((btn) => {
  btn.addEventListener("click", () => {
    // Get data from button
    id2.value = btn.dataset.id;
    dateDEbut.value = btn.dataset.date;
    prix.value = btn.dataset.prix;

    // Scroll to form
    window.scrollTo({
      top: 0,
      behavior: "smooth",
    });
  });
});
