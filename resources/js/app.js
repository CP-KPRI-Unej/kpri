import "./bootstrap";

document.addEventListener("DOMContentLoaded", function () {
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
