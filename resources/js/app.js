import "./bootstrap";

import Alpine from "alpinejs";

window.Alpine = Alpine;

Alpine.start();

// Dark Mode Toggle Functionality
document.addEventListener("DOMContentLoaded", function () {
    console.log("DOM loaded, setting up dark mode toggle...");

    const themeToggle = document.getElementById("theme-toggle");
    console.log("Theme toggle button found:", themeToggle);

    if (themeToggle) {
        themeToggle.addEventListener("click", function (e) {
            e.preventDefault();
            console.log("Theme toggle clicked");

            // Toggle dark mode
            if (document.documentElement.classList.contains("dark")) {
                console.log("Switching to light mode");
                document.documentElement.classList.remove("dark");
                localStorage.theme = "light";
            } else {
                console.log("Switching to dark mode");
                document.documentElement.classList.add("dark");
                localStorage.theme = "dark";
            }
        });
    } else {
        console.log("Theme toggle button not found!");
    }

    // Also set up theme toggle for any dynamically loaded content
    document.addEventListener("click", function (e) {
        if (e.target.closest("#theme-toggle")) {
            e.preventDefault();
            console.log("Theme toggle clicked via delegation");

            // Toggle dark mode
            if (document.documentElement.classList.contains("dark")) {
                console.log("Switching to light mode");
                document.documentElement.classList.remove("dark");
                localStorage.theme = "light";
            } else {
                console.log("Switching to dark mode");
                document.documentElement.classList.add("dark");
                localStorage.theme = "dark";
            }
        }
    });
});
