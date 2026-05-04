document.addEventListener("DOMContentLoaded", function() {
  const thumbs = document.querySelectorAll(".ci-thumb");
  const main = document.getElementById("ci-main-img");

  thumbs.forEach(thumb => {
    thumb.addEventListener("click", function() {
      main.src = this.dataset.full;
    });
  });
});