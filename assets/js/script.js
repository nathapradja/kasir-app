const togglebutton = document.getElementById("menu-toggle");
const sidebar = document.getElementById("sidebar");

togglebutton.addEventListener("click", () => {
    sidebar.classList.toggle("active");
});