function deleteForm() {
  // TODO
}

window.addEventListener("load", function () {
  let isDeleteEnabled = "<?php print($display_edit_button); ?>";
  let deleteForm = document.getElementById('delete-form');
  deleteForm.style.display = isDeleteEnabled ? 'block' : 'none';

});