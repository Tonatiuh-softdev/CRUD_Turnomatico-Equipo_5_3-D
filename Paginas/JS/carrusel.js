let index = 0;
const images = document.querySelectorAll(".carousel-img");
const carousel = document.querySelector(".carousel");

function showImage(i) {
    index = (i + images.length) % images.length;
    carousel.style.transform = `translateX(${-index * 100}%)`;
}

document.addEventListener("DOMContentLoaded", () => {
    const prevBtn = document.querySelector(".prev-btn");
    const nextBtn = document.querySelector(".next-btn");

    if (prevBtn && nextBtn) {
        prevBtn.addEventListener("click", () => showImage(index - 1));
        nextBtn.addEventListener("click", () => showImage(index + 1));
    }

    /* ======= MODAL ======= */
    const modal = document.getElementById("modal");
    const modalImg = document.getElementById("modal-img");
    const closeModal = document.querySelector(".close");

    images.forEach(img => {
        img.addEventListener("click", () => {
            modal.style.display = "block";
            modalImg.src = img.src;
        });
    });

    closeModal.onclick = () => modal.style.display = "none";

    modal.onclick = (e) => {
        if (e.target === modal) modal.style.display = "none";
    };
});
