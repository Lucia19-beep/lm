document.addEventListener("DOMContentLoaded", function () {
    const perfil = document.querySelector(".perfil-hover");
    const menu = document.getElementById("logoutMenu");

    if (perfil && menu) {
        perfil.addEventListener("mouseover", () => {
            menu.style.display = "block";
        });

        perfil.addEventListener("mouseout", () => {
            menu.style.display = "none";
        });
    }
});
