import "./bootstrap";

import Alpine from "alpinejs";

window.Alpine = Alpine;

Alpine.start();

// Dark Mode Toggle Functionality
document.addEventListener("DOMContentLoaded", function () {
    const themeToggle = document.getElementById("theme-toggle");

    if (themeToggle) {
        themeToggle.addEventListener("click", function () {
            // Toggle dark mode
            if (document.documentElement.classList.contains("dark")) {
                document.documentElement.classList.remove("dark");
                localStorage.theme = "light";
            } else {
                document.documentElement.classList.add("dark");
                localStorage.theme = "dark";
            }
        });
    }
});
