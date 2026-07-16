const id3 = document.getElementById("id3");
const entraineur = document.getElementById("entraineur");
const assu = document.getElementById("assu");
const activite = document.getElementById("activite");

const editButtons3 = document.querySelectorAll(".editBtn3");

editButtons3.forEach((btn) => {
    btn.addEventListener("click", () => {
        // Get data from button
        id3.value = btn.dataset.id;
        entraineur.value = btn.dataset.entr;
        assu.value = btn.dataset.ass;
        activite.value = btn.dataset.name;

        // Scroll to form
        window.scrollTo({
            top: 0,
            behavior: "smooth",
        });
    });
});
