/* ---------------- NAV & IMAGE LOGIC ---------------- */
let navbar = document.querySelector(".header .flex .navbar");
let profile = document.querySelector(".header .flex .profile");

document.querySelector("#menu-btn").onclick = () => {
  navbar.classList.toggle("active");
  profile.classList.remove("active");
};

document.querySelector("#user-btn").onclick = () => {
  profile.classList.toggle("active");
  navbar.classList.remove("active");
};

window.onscroll = () => {
  navbar.classList.remove("active");
  profile.classList.remove("active");
};

let mainImage = document.querySelector(".quick-view .main-image img");
let subImages = document.querySelectorAll(".quick-view .sub-image img");

subImages.forEach((img) => {
  img.onclick = () => (mainImage.src = img.src);
});

/* ---------------- PAYMENT LOGIC ---------------- */

const DEMO_NUMBER = "9840318943";
const DEMO_PIN = "1234";

const modal = document.getElementById("payment-modal");
const methodSelect = document.querySelector("select[name='method']");

// open modal when method is esewa/khalti
methodSelect.addEventListener("change", function () {
  if (this.value === "paytm") {
    document.getElementById("pay-logo").src = "images/esewa.png";
    document.getElementById("pay-title").innerText = "eSewa Payment";
    modal.classList.remove("hidden");

  } else if (this.value === "paypal") {
    document.getElementById("pay-logo").src = "images/khalti.png";
    document.getElementById("pay-title").innerText = "Khalti Payment";
    modal.classList.remove("hidden");

  } else {
    modal.classList.add("hidden");
  }
});

function closePaymentModal() {
  modal.classList.add("hidden");
}


/* ---------------- CONFIRM PAYMENT ---------------- */
function confirmPayment() {

  let number = document.getElementById("payment-number").value.trim();
  let pin = document.getElementById("payment-pin").value.trim();

  if (number === "") {
    alert("Please enter your mobile number.");
    return;
  }
  if (pin === "") {
    alert("Please enter your payment PIN.");
    return;
  }
  if (number !== DEMO_NUMBER) {
    alert("❌ Incorrect Number!");
    return;
  }
  if (pin !== DEMO_PIN) {
    alert("❌ Incorrect PIN!");
    return;
  }

  // *********** FIXED ***********
  alert("✔ Payment Verified!");
  closePaymentModal();  // ONLY CLOSE MODAL — NO FORM SUBMIT
}
