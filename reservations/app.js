const dateFromInput = document.getElementById("date_from");
const dateToInput = document.getElementById("date_to");
const totalPriceInput = document.getElementById("total_price");
const pricePerDayInput = document.getElementById("price_per_day");
const totalPriceLabel = document.getElementById("total_price_label");
const updateMessage = document.getElementById("update_message");
const reservationsGrid = document.getElementById("reservations-grid");

var editModal = document.getElementById("editReservationModal");
editModal.addEventListener("show.bs.modal", async function (event) {
  // Button that triggered the modal
  var button = event.relatedTarget;
  var reservation = JSON.parse(button.getAttribute("data-bs-reservation"));
  document.getElementById("id").value = reservation.id;
  document.getElementById("car_id").value = reservation.car_id;
  dateFromInput.value = reservation.date_from;
  dateToInput.value = reservation.date_to;
  totalPriceInput.value = reservation.totalPrice;
  pricePerDayInput.value = reservation.price_per_day;
  totalPriceLabel.innerHTML = `Total price: ${reservation.total_price}€`;
});

function totalPrice() {
  var date_from = new Date(dateFromInput.value);
  var date_to = new Date(dateToInput.value);
  var difference = date_to.getTime() - date_from.getTime();
  var pricePerDay = pricePerDayInput.value;
  var totalPrice = Math.ceil(difference / (1000 * 3600 * 24)) * pricePerDay;
  totalPriceInput.value = totalPrice;
  totalPriceLabel.innerHTML = `Total price: ${totalPrice}€`;
}

async function updateReservation(event) {
  event.preventDefault();

  var response = await fetch(
    "update.php?" + new URLSearchParams(new FormData(event.target))
  );

  var responseText = await response.text();

  if (responseText == 1) {
    updateMessage.innerHTML = `
    <div class="col-6 offset-3">
        <div class="alert alert-success" role="alert">
            You sucessfully updated your reservation!
        </div>
    </div>
    `;
  } else if (responseText == "alreadyReserved") {
    updateMessage.innerHTML = `
    <div class="col-6 offset-3">
        <div class="alert alert-danger" role="alert">
            Reservation in that date already exists!
        </div>
    </div>
    `;
  } else {
  }
  updateMessage.classList.remove("d-none");
  updateMessage.scrollIntoView(true);
  function hideMessage() {
    updateMessage.classList.add("d-none");
  }
  setTimeout(hideMessage, 2000);
  refreshReservations();
}

async function refreshReservations() {
  fetch("reservationsDisplay.php")
    .then((response) => response.text())
    .then((responseText) => (reservationsGrid.innerHTML = responseText));
}

async function cancelReservation(event) {
  event.preventDefault();

  var response = await fetch(
    "cancel.php?" + new URLSearchParams(new FormData(event.target))
  );

  var responseText = await response.text();

  if (responseText == 1) {
    updateMessage.innerHTML = `
    <div class="col-6 offset-3">
        <div class="alert alert-success" role="alert">
            You sucessfully cancelled your reservation!
        </div>
    </div>
    `;
  } else {
    updateMessage.innerHTML = `
    <div class="col-6 offset-3">
        <div class="alert alert-danger" role="alert">
            Reservation cancel failed!
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
  refreshReservations();
}
