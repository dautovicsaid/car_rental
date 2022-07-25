const apiUrl = "http://localhost/car_rental";
const updateMessage = document.getElementById("update_message");
const usersTableBody = document.getElementById("usersTableBody");

async function refreshUsers() {
  fetch("usersDisplay.php")
    .then((response) => response.text())
    .then((responseText) => (usersTableBody.innerHTML = responseText));
}

async function deactivateUser(event) {
  event.preventDefault();
  var response = await fetch(apiUrl + "/administration/deactivateUser.php?", {
    method: "post",
    body: new FormData(event.target),
  });
  var responseText = await response.text();

  if (responseText == 1) {
    updateMessage.innerHTML = `
        <div class="col-6 offset-3">
            <div class="alert alert-success" role="alert">
                You sucessfully deactivated user!
            </div>
        </div>
    `;
  } else {
    updateMessage.innerHTML = `
        <div class="col-6 offset-3">
            <div class="alert alert-danger" role="alert">
                User deactivation failed!
            </div>
        </div>
    `;
  }
  updateMessage.classList.remove("d-none");
  updateMessage.scrollIntoView(true);
  function hideMessage() {
    updateMessage.classList.add("d-none");
  }
  setTimeout(hideMessage, 2000);
  refreshUsers();
}
