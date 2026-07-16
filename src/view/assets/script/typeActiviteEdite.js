const id4 = document.getElementById("id4");
const TypeActivite = document.getElementById("typeActivite");
const activite2 = document.getElementById("act"); 

const editButtons4 = document.querySelectorAll(".editBtn4");

editButtons4.forEach((btn) => {
  btn.addEventListener("click", () => {
    // Get data from button
    id4.value = btn.dataset.id;
    TypeActivite.value = btn.dataset.name;
    activite2.value = btn.dataset.Acti; 

    // Scroll to form
    window.scrollTo({
      top: 0,
      behavior: "smooth",
    });
  });
});
