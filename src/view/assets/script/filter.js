const filter = document.getElementById("filter");
function filterUsers(e) {
  const text = e.target.value.toLowerCase();
  const users = document.querySelectorAll("tbody tr");
  users.forEach((user) => {
    const name = user.textContent.toLowerCase();

    if (name.indexOf(text) != -1) {
      user.style.display = "table-row";
    } else {
      user.style.display = "none";
    }
  });
}
filter.addEventListener("input", filterUsers);
