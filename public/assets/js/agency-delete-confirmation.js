"use strict";

const agencyDeleteForms = document.querySelectorAll(
  "[data-agency-delete-form]",
);

agencyDeleteForms.forEach((form) => {
  form.addEventListener("submit", (event) => {
    const isConfirmed = window.confirm(
      "Confirmez-vous la suppression de cette agence ?",
    );

    if (!isConfirmed) {
      event.preventDefault();
    }
  });
});