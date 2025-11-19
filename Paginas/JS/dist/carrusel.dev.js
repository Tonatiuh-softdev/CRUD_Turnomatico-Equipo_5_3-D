"use strict";

var index = 0;
var images = document.querySelectorAll(".carousel-img");
var carousel = document.querySelector(".carousel");

function showImage(i) {
  index = (i + images.length) % images.length;
  carousel.style.transform = "translateX(".concat(-index * 100, "%)");
}

document.addEventListener("DOMContentLoaded", function () {
  var prevBtn = document.querySelector(".prev-btn");
  var nextBtn = document.querySelector(".next-btn");

  if (prevBtn && nextBtn) {
    prevBtn.addEventListener("click", function () {
      return showImage(index - 1);
    });
    nextBtn.addEventListener("click", function () {
      return showImage(index + 1);
    });
  }
  /* ======= MODAL ======= */


  var modal = document.getElementById("modal");
  var modalImg = document.getElementById("modal-img");
  var closeModal = document.querySelector(".close");
  images.forEach(function (img) {
    img.addEventListener("click", function () {
      modal.style.display = "block";
      modalImg.src = img.src;
    });
  });

  closeModal.onclick = function () {
    return modal.style.display = "none";
  };

  modal.onclick = function (e) {
    if (e.target === modal) modal.style.display = "none";
  };
});
//# sourceMappingURL=carrusel.dev.js.map
