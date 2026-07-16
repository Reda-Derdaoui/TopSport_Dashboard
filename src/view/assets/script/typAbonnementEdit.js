const Id = document.getElementById("id");
const Type = document.getElementById("type_abonnement");

const editButtons = document.querySelectorAll(".editBtn");

editButtons.forEach((btn) => {
  btn.addEventListener("click", () => {
    // Get data from button
    Id.value = btn.dataset.id;
    Type.value = btn.dataset.name;

    // Scroll to form
    window.scrollTo({
      top: 0,
      behavior: "smooth",
    });
  });
});
