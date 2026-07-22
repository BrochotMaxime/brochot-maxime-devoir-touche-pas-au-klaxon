"use strict";

const tripDetailsModal = document.getElementById("trip-details-modal");

if (tripDetailsModal !== null) {
  tripDetailsModal.addEventListener("show.bs.modal", (event) => {
    const trigger = event.relatedTarget;

    if (!(trigger instanceof HTMLElement)) {
      return;
    }

    const authorElement = tripDetailsModal.querySelector(
      '[data-trip-detail="author"]',
    );
    const phoneElement = tripDetailsModal.querySelector(
      '[data-trip-detail="phone"]',
    );
    const emailElement = tripDetailsModal.querySelector(
      '[data-trip-detail="email"]',
    );
    const totalSeatsElement = tripDetailsModal.querySelector(
      '[data-trip-detail="total-seats"]',
    );

    if (authorElement !== null) {
      authorElement.textContent = trigger.dataset.tripAuthor ?? "";
    }

    if (phoneElement !== null) {
      phoneElement.textContent = trigger.dataset.tripPhone ?? "";
    }

    if (emailElement !== null) {
      emailElement.textContent = trigger.dataset.tripEmail ?? "";
    }

    if (totalSeatsElement !== null) {
      totalSeatsElement.textContent = trigger.dataset.tripTotalSeats ?? "";
    }
  });
}