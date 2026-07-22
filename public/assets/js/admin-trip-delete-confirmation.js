"use strict";

const adminTripDeleteForms = document.querySelectorAll(
  "[data-admin-trip-delete-form]",
);

adminTripDeleteForms.forEach((form) => {
  form.addEventListener("submit", (event) => {
    const isConfirmed = window.confirm(
      "Confirmez-vous la suppression de ce trajet ?",
    );

    if (!isConfirmed) {
      event.preventDefault();
    }
  });
});