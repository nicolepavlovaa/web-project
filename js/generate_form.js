window.addEventListener("load", function () {
  fileInput = document.getElementById("file-input");
  fileInput2 = document.getElementById("file-input-2");
  let reader = new FileReader();

  fileInput.onchange = function (event) {
    let fileList = fileInput.files;
    let JsonObj;

    f = fileList.item(0);
    let name = f.name;

    // display the filename
    fileName = document.getElementById("filename");
    fileName.innerHTML = name;
    fileName.style.backgroundColor = "white";

    // Closure to capture the file information.
    reader.onload = (function (theFile) {
      return function (e) {
        // Render thumbnail.
        JsonObj = JSON.parse(e.target.result);
        document.getElementById('gform').value = JSON.stringify(JsonObj, null, 2);
      };
    })(f);

    // Read in the image file as a data URL.
    reader.readAsText(f);
  }

  fileInput2.onchange = function (event) {
    let fileList = fileInput2.files;
    let obj;

    f = fileList.item(0);
    let name = f.name;

    // display the filename
    fileName = document.getElementById("filename-2");
    fileName.innerHTML = name;
    fileName.style.backgroundColor = "white";

    // Closure to capture the file information.
    reader.onload = (function (theFile) {
      return function (e) {
        // Render thumbnail.
        obj = e.target.result;
        document.getElementById('form-content').value = obj;
      };
    })(f);

    // Read in the image file as a data URL.
    reader.readAsText(f);
  }

  var popup = document.getElementById("modal");
  var overlay = document.getElementById('background-overlay');
  document.onclick = function (e) {
    if (e.target.id == 'background-overlay') {
      popup.style.display = 'none';
      overlay.style.display = 'none';
    }
  };
});
