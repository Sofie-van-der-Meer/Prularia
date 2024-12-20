"use strict";

// Variables used
let password = document.getElementById("password");
let password2 = document.getElementById("password2");

let companyPassword = document.getElementById("company-password");
let companyPassword2 = document.getElementById("company-password2");

let power = document.getElementById("power-point");
let power2 = document.getElementById("power-point2");

let errorMessage = document.getElementById("password-error");
let errorMessageCompany = document.getElementById("company-password-error");

let requirementsList = document.getElementById("password-requirements");
let passwordTitle = document.getElementById("password-title");

let requirementsListCompany = document.getElementById("password-requirements-company");
let passwordTitleCompany = document.getElementById("password-title-company");

let showPasswordUser = document.getElementById("user-showpass");
let showPasswordCompany = document.getElementById("company-showpass");

// Show password
showPasswordUser.addEventListener("click", () => showPassword(password, password2));
showPasswordCompany.addEventListener("click", () => showPassword(companyPassword, companyPassword2));

// Validate passwords
password.addEventListener("input", () => validatePasswords("user"));
password2.addEventListener("input", () => validatePasswords("user"));
password.addEventListener("input", () => validatePasswordRequirements("user"));

companyPassword.addEventListener("input", () => validatePasswords("company"));
companyPassword2.addEventListener("input", () => validatePasswords("company"));
companyPassword.addEventListener("input", () => validatePasswordRequirements("company"));

// Validate passwords function
function validatePasswords(type) {
  if (type === "user") {
    if (password.value !== password2.value) {
      errorMessage.textContent = "De wachtwoorden komen niet overeen.";
      errorMessage.classList.remove("hidden");
    } else {
      errorMessage.textContent = "";
      errorMessage.classList.add("hidden");
    }
  } else if (type === "company") {
    if (companyPassword.value !== companyPassword2.value) {
      errorMessageCompany.textContent = "De wachtwoorden komen niet overeen.";
      errorMessageCompany.classList.remove("hidden");
    } else {
      errorMessageCompany.textContent = "";
      errorMessageCompany.classList.add("hidden");
    }
  }

  updatePasswordStrength(type);
}

function updatePasswordStrength(type) {
  let point = 0;
  let arrayTest = [/[0-9]/, /[a-z]/, /[A-Z]/, /[^0-9a-zA-Z]/];

  let currentPassword = type === "user" ? password.value : companyPassword.value;
  let currentPower = type === "user" ? power : power2;

  if (currentPassword.length >= 8) {
    arrayTest.forEach((item) => {
      if (item.test(currentPassword)) {
        point += 2;
      }
    });
  }

  let percentage = Math.min(point * 12.5, 100);
  let color;

  if (percentage <= 24) {
    color = "#D73F40";
  } else if (percentage <= 50) {
    color = "#F2B84F";
  } else if (percentage <= 75) {
    color = "#BDE952";
  } else {
    color = "#3ba62f";
  }

  currentPower.style.width = percentage + "%";
  currentPower.style.backgroundColor = color;
}

// Function to validade password requirements
function validatePasswordRequirements(type) {
  const requirements = [
    { id: "length", test: (pwd) => pwd.length >= 8 },
    { id: "uppercase", test: (pwd) => /[A-Z]/.test(pwd) },
    { id: "lowercase", test: (pwd) => /[a-z]/.test(pwd) },
    { id: "number", test: (pwd) => /[0-9]/.test(pwd) },
    { id: "special", test: (pwd) => /[^0-9a-zA-Z]/.test(pwd) },
  ];

  let allValid = true;

  if (type === "user") {
    requirements.forEach(({ id, test }) => {
      let item = requirementsList.querySelector(`[data-requirement="${id}"]`);

      if (test(password.value)) {
        item.style.display = "none";
      } else {
        item.style.display = "list-item";
        allValid = false;
      }
    });

    passwordTitle.innerHTML = allValid ? "<strong>Wachtwoord voorwaarden: Volstaan</strong>" : "<strong>Wachtwoord voorwaarden:</strong>";
    passwordTitle.style.color = allValid ? "green" : "red";
  } else if (type === "company") {
    requirements.forEach(({ id, test }) => {
      let itemCompany = requirementsListCompany.querySelector(`[data-requirement="${id}"]`);

      if (test(companyPassword.value)) {
        itemCompany.style.display = "none";
      } else {
        itemCompany.style.display = "list-item";
        allValid = false;
      }
    });

    passwordTitleCompany.innerHTML = allValid ? "<strong>Wachtwoord voorwaarden: Volstaan</strong>" : "<strong>Wachtwoord voorwaarden:</strong>";
    passwordTitleCompany.style.color = allValid ? "green" : "red";
  }
}

// Show/hide password function
function showPassword(password, password2) {
  if (password.type === "password" && password2.type === "password") {
    password.type = "text";
    password2.type = "text";
  } else {
    password.type = "password";
    password2.type = "password";
  }
}

// Toggle form - type of account

function toggleForm(type) {
  document.getElementById("private-person-form").classList.add("hidden");
  document.getElementById("business-form").classList.add("hidden");
  if (type === "private") {
    document.getElementById("private-person-form").classList.remove("hidden");
  } else if (type === "business") {
    document.getElementById("business-form").classList.remove("hidden");
  }
}

// Person Billing Address !== Delivery Address
function addressFunction() {
  const checkbox = document.getElementById("set-delivery-address");
  const deliveryAddress = document.getElementById("delivery-address-field");

  if (checkbox.checked) {
    deliveryAddress.classList.remove("hidden");
  } else {
    deliveryAddress.classList.add("hidden");
  }
}

// Function to capture form data and log it - used for testing
function logFormData(event) {
  event.preventDefault();

  const form = event.target;

  const formData = new FormData(form);

  const formDataObj = {};
  formData.forEach((value, key) => {
    formDataObj[key] = value;
  });

  console.log("Form Data Submitted:", formDataObj);
}
