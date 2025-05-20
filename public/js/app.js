document.addEventListener("DOMContentLoaded", function () {
    const savedTheme = localStorage.getItem("theme");
    if (savedTheme === "dark") {
        document.body.classList.add("dark-mode");
    }

    const themeToggle = document.querySelector(".toggle-theme");
    if (themeToggle) {
        themeToggle.addEventListener("click", function () {
            if (document.body.classList.contains("dark-mode")) {
                localStorage.setItem("theme", "light");
            } else {
                localStorage.setItem("theme", "dark");
            }
        });
    }

    document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
        anchor.addEventListener("click", function (e) {
            e.preventDefault();

            const targetId = this.getAttribute("href");
            if (targetId === "#") return;

            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: "smooth",
                });
            }
        });
    });

    const animateOnScroll = function () {
        const elements = document.querySelectorAll(".animate-on-scroll");

        elements.forEach((element) => {
            const elementPosition = element.getBoundingClientRect().top;
            const windowHeight = window.innerHeight;

            if (elementPosition < windowHeight - 100) {
                element.classList.add("animated");
            }
        });
    };

    const sections = document.querySelectorAll("section");
    sections.forEach((section) => {
        section.classList.add("animate-on-scroll");
    });

    window.addEventListener("scroll", animateOnScroll);
    animateOnScroll();
});
