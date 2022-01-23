function deleteForm() {
  const urlParams = new URLSearchParams(window.location.search);
  const id = urlParams.get('form_id');

  var formData = new FormData();
  formData.append('id', id);

  let path = window.location.pathname;
  let directory = path.substring(path.indexOf('/'), path.lastIndexOf('/'));
  let urlBase = directory == '/' ? '' : directory;

  fetch(`..${urlBase}/private/delete_form.php`, {
    method: 'POST',
    body: formData,
  }).then(function (response) {
    if (response.status >= 200 && response.status < 300) {
      return response.text()
    }
    throw new Error(response.statusText)
  })
    .then(function (response) {
      console.log(response);
    });
  window.location.replace("index");
}

window.addEventListener("load", function () {
  let isDeleteEnabled = "<?php print($display_edit_button); ?>";
  let deleteForm = document.getElementById('delete-form');
  deleteForm.style.display = isDeleteEnabled ? 'block' : 'none';

  var popup = document.getElementById("modal");
  var overlay = document.getElementById('background-overlay');
  document.onclick = function (e) {
    if (e.target.id == 'background-overlay') {
      popup.style.display = 'none';
      overlay.style.display = 'none';
    }
  };
});