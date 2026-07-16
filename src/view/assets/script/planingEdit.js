const id = document.getElementById("id");
const activity = document.getElementById("activity");
const day = document.getElementById("jour");
const time = document.getElementById("time");
const coach = document.getElementById("coach");

const editButtons = document.querySelectorAll(".editBtn");

editButtons.forEach((btn) => {
  btn.addEventListener("click", () => {
    // Get data from button
    id.value = btn.dataset.id;
    activity.value =btn.dataset.name; 
    day.value = btn.dataset.day; 
    time.value = btn.dataset.time; 
    coach.value = btn.dataset.coach; 

      // Scroll to form
      window.scrollTo({
        top: 0,
        behavior: "smooth",
      });
  });
});
