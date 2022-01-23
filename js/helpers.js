function openForm(id) {
  location.href = `form?form_id=${id}`;
}

function logout() {
  document.cookie = 'auth' + '=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
  location.assign("login");
}

function openModal() {
  let popup = document.getElementById("modal");
  var overlay = document.getElementById('background-overlay');
  popup.style.display = 'block';
  overlay.style.display = 'block';
}
