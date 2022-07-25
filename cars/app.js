const apiUrl = "http://localhost/car_rental";

const updateMessage = document.getElementById("update_message");
const dateFromInput = document.getElementById("date_from");
const dateToInput = document.getElementById("date_to");
const totalPriceInput = document.getElementById("total_price");
const pricePerDayInput = document.getElementById("price_per_day");
const totalPriceLabel = document.getElementById("total_price_label");
const carForm = document.getElementById("carForm");
const carModal = document.getElementById("carModal");
const closeCarUpdateModalBtn = document.getElementById(
  "closeCarUpdateModalBtn"
);
const createCarInputs = {
  brand_id: document.getElementsByName("brand")[0],
  model_id: document.getElementsByName("model")[0],
  class_id: document.getElementsByName("class")[0],
  register_number: document.getElementsByName("register_number")[0],
  production_year: document.getElementsByName("production_year")[0],
  price_per_day: document.getElementsByName("price")[0],
};

const updateCarInputs = {
  brand_id: document.getElementsByName("brand")[1],
  model_id: document.getElementsByName("model")[1],
  class_id: document.getElementsByName("class")[1],
  register_number: document.getElementsByName("register_number")[0],
  production_year: document.getElementsByName("production_year")[0],
  price_per_day: document.getElementsByName("price")[0],
};

async function filterCars(event = "") {
  event != "" ? event.preventDefault() : "";
  //const response = await fetch("filterCars.php?"+new URLSearchParams(new FormData(event.target)))

  fetch(
    "cars/filterCars.php?" + new URLSearchParams(new FormData(event.target))
  )
    .then(function (response) {
      // The API call was successful!
      return response.text();
    })
    .then(function (html) {
      // This is the HTML from our response as a text string
      var myElement = document.getElementById("carsList");
      myElement.innerHTML = html;
      event != "" ? myElement.scrollIntoView(true) : "";
    });
}

async function loadModels() {
  let brandId = document.getElementById("brand").value;
  let response = await fetch(
    `${apiUrl}/cars/getModels.php?brand_id=${brandId}`
  );
  let models = await response.json();

  let modelsHTML = "";
  models.forEach((model) => {
    modelsHTML += `<option value="${model.id}" >${model.name}</option>`;
  });

  document.getElementById("model").innerHTML = modelsHTML;
}

async function loadModelsForFilter() {
  let brandId = document.getElementById("filter_brand").value;
  let response = await fetch(
    `${apiUrl}/cars/getModels.php?brand_id=${brandId}`
  );
  let models = await response.json();

  let modelsHTML = "<option selected value=''>- Choose model -</option>";
  models.forEach((model) => {
    modelsHTML += `<option value="${model.id}" >${model.name}</option>`;
  });

  document.getElementById("filter_model").innerHTML = modelsHTML;
}

function hideMessage() {
  updateMessage.classList.add = "d-none";
}

async function updateCar(event) {
  event.preventDefault();
  var response = await fetch(apiUrl + "/cars/update.php?", {
    method: "post",
    body: new FormData(event.target),
  });

  var responseText = await response.text();

  if (responseText == 1) {
    updateMessage.innerHTML = `
    <div class="col-6 offset-3">
        <div class="alert alert-success" role="alert">
            You sucessfully updated car!
        </div>
    </div>
    `;
  } else {
    updateMessage.innerHTML = `
    <div class="col-6 offset-3">
        <div class="alert alert-danger" role="alert">
            Car update failed!
        </div>
    </div>
    `;
  }
  updateMessage.classList.remove("d-none");
  updateMessage.scrollIntoView(true);
  setTimeout(hideMessage, 2000);
  filterCars();
  closeCarUpdateModalBtn.click();
}

function totalPrice() {
  var date_from = new Date(dateFromInput.value);
  var date_to = new Date(dateToInput.value);
  var difference = date_to.getTime() - date_from.getTime();
  var pricePerDay = pricePerDayInput.value;
  var totalPrice = "";
  if (date_to != "Invalid Date") {
    totalPrice = Math.ceil(difference / (1000 * 3600 * 24)) * pricePerDay;
    totalPriceInput.value = totalPrice;
    totalPriceLabel.innerHTML = `Total price: ${totalPrice}€`;
  }
}

function validateCar(event) {
  event.preventDefault();

  let formData = {};

  Object.keys(createCarInputs).forEach(
    (inputName) => (formData[inputName] = createCarInputs[inputName].value)
  );

  if (!validateCarFormData(formData)) {
    return;
  }

  carForm.submit();
}

function validateCarFormData(car) {
  let errors = {
    brand_id: null,
    model_id: null,
    class_id: null,
    register_number: null,
    production_year: null,
    price_per_day: null,
    //photos: null,
  };

  if (car.brand_id < 1) {
    errors.brand_id = "Please select brand";
  }

  if (car.model_id < 1) {
    errors.model_id = "Please select model";
  }

  if (car.class_id < 1) {
    errors.class_id = "Please select class";
  }

  //Uzeto u obzir da su nase tablice u pitanju... Primjer: PG CG 001
  if (car.register_number.length != 7) {
    errors.register_number = "Please insert valid register number";
  }

  if (car.production_year.length != 4) {
    errors.production_year = "Please insert valid year of production";
  } else if (car.production_year < 1970 || car.production_year > 2022) {
    errors.production_year =
      "Year of productions must be between 1970 and 2022";
  }

  if (car.price_per_day < 1) {
    errors.price_per_day = "Please insert valid price";
  }

  for (let property of Object.keys(errors)) {
    if (!(errors[property] === null)) {
      displayCarErrors(errors);
      return false;
    }
  }

  return true;
}

function displayCarErrors(errors) {
  for (let property of Object.keys(errors)) {
    let errorMessage = errors[property];
    let errorSpan = document.getElementById(`error-${property}`);

    if (!(errorMessage == null)) {
      errorSpan.classList.remove("d-none");
      errorSpan.innerHTML = errorMessage;
    } else {
      errorSpan.innerHTML = "";
      errorSpan.classList.add("d-none");
    }
  }
}

function validateAndUpdateCarModal(event) {
  event.preventDefault();

  let formData = {};

  Object.keys(updateCarInputs).forEach(
    (inputName) => (formData[inputName] = createCarInputs[inputName].value)
  );

  if (!validateCarFormData(formData)) {
    return;
  }
  updateCar(event);
  carForm.reset();
  closeCarUpdateModalBtn.click();
}

async function deleteCar(event) {
  event.preventDefault();

  var response = await fetch(
    apiUrl +
      "/cars/delete.php?" +
      new URLSearchParams(new FormData(event.target))
  );

  var responseText = await response.text();

  if (responseText == 1) {
    updateMessage.innerHTML = `
    <div class="col-6 offset-3">
        <div class="alert alert-success" role="alert">
            You sucessfully deleted car!
        </div>
    </div>
    `;
  } else {
    updateMessage.innerHTML = `
    <div class="col-6 offset-3">
        <div class="alert alert-danger" role="alert">
            Car delete failed!
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
  filterCars();
}

function clearUpdateCarModal() {
  let inputs = document.getElementsByClassName("input-error");
  for (let input of inputs) {
    input.innerHTML = "";
    input.classList.add("d-none");
  }
}
