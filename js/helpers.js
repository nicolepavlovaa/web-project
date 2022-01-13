function openForm(id) {
  location.href = `form?form_id=${id}`;

}

function logout() {
  document.cookie = 'auth' + '=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
  location.assign("login");
}