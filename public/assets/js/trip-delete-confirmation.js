"use strict";

const tripDeleteForms = document.querySelectorAll(
  "[data-trip-delete-form]",
);

tripDeleteForms.forEach((form) => {
  form.addEventListener("submit", (event) => {
    const isConfirmed = window.confirm(
      "Confirmez-vous la suppression de ce trajet ?",
    );

    if (!isConfirmed) {
      event.preventDefault();
    }
  });
});