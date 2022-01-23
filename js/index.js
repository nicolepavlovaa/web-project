window.addEventListener("load", function () {
  var popup = document.getElementById("modal");
  var overlay = document.getElementById('background-overlay');
  document.onclick = function (e) {
    if (e.target.id == 'background-overlay') {
      popup.style.display = 'none';
      overlay.style.display = 'none';
    }
  };
});