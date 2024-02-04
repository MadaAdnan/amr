// window.addEventListener("DOMContentLoaded", () => {
//   const roundTripRadio = document.getElementById("round-trip");
//   roundTripRadio.checked = true;
// });
// document.getElementById("one-way-trip").addEventListener("change", () => {
//   const timeElement = document.getElementById("hidden");
//   const dateElement = document.getElementById("hiddenWidth");

//   dateElement.classList.add("d-none");
//   timeElement.classList.add("d-none");
// });

// document.getElementById("round-trip").addEventListener("change", () => {
//   const timeElement = document.getElementById("hidden");
//   const dateElement = document.getElementById("hiddenWidth");
//   const ChangeTimeWidth = document.getElementById("ChangeTimeWidthTrip");

  // ChangeTimeWidth.style.width = "25%";
  // timeElement.classList.remove("d-none");
  // dateElement.classList.remove("d-none");
// });
// const y = document.getElementById("y");

// let a;
// let b;

//Disapled input
// y.addEventListener("change", () => {
//   if (y.value >= 3) {
//     document.getElementById("a1").disabled = true;
//     document.getElementById("a2").disabled = true;
//     a = document.getElementById("a1").value;
//     b = document.getElementById("a2").value;

//     document.getElementById("a1").value = null;
//     document.getElementById("a2").value = null;
//   } else {
//     document.getElementById("a1").disabled = false;
//     document.getElementById("a2").disabled = false;
//     document.getElementById("a1").value = a;
//     document.getElementById("a2").value = b;
//   }
// });

// // change

// const hourly = document.getElementById("hourly");
// const pointToPoint = document.getElementById("point-to-point");

// const hourlyBtn = document.getElementById("hourly-btn");
// const pointToPointBtn = document.getElementById("point-to-point-btn");

// // Select all filter buttons and filterable cards
// const filterButtons = document.querySelectorAll(".filter-buttons ");
// const filterableCards = document.querySelectorAll(".filter-cards");

// // Select all filter buttons and filterable cards
// const filterButtons = document.querySelectorAll(".filter-buttons ");
// const filterableCards = document.querySelectorAll(".filter-cards");

// // Define the filterCards function
// function filterCards(e) {
//   document.querySelector(".active-btn").classList.remove("active-btn");
//   e.target.classList.add("active-btn");
// }

// // Add click event listener to each filter button
// filterButtons.forEach((button) =>
//   button.addEventListener("click", filterCards)
// );
