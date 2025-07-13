const basket = [];
const basketIcon = document.getElementById("basket-icon");
const basketModal = document.getElementById("basket-modal");
const basketList = document.getElementById("basket-list");
const basketTotal = document.getElementById("basket-total");
const basketCount = document.getElementById("basket-count");
const closeBasket = document.getElementById("close-basket");

document.querySelectorAll(".buy-btn").forEach((btn) => {
  btn.addEventListener("click", function () {
    const card = btn.closest(".product-card");
    const name = card.getAttribute("data-name");
    const price = parseFloat(card.getAttribute("data-price"));
    basket.push({
      name,
      price,
    });
    basketCount.textContent = basket.length;
  });
});

document.getElementById("buy-btn-modal").addEventListener("click", function () {
  if (basket.length === 0) return;

  fetch(ajaxurl.ajaxurl, {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body:
      "action=submit_order&order_data=" +
      encodeURIComponent(JSON.stringify(basket)),
  })
    .then((res) => res.text())
    .then((response) => {
      alert("Η παραγγελία καταχωρήθηκε!");
      basket.length = 0; // empty basket
      basketCount.textContent = 0;
      basketModal.style.display = "none";
    })
    .catch((err) => {
      alert("Σφάλμα κατά την καταχώρηση της παραγγελίας.");
      console.error(err);
    });
});

basketIcon.addEventListener("click", function () {
  basketList.innerHTML = "";
  let total = 0;
  basket.forEach((item) => {
    const li = document.createElement("li");
    li.textContent = item.name + " - €" + item.price.toFixed(2);
    basketList.appendChild(li);
    total += item.price;
  });
  basketTotal.textContent = "Συνολικό ποσό: €" + total.toFixed(2);
  basketModal.style.display = "block";
});

closeBasket.addEventListener("click", function () {
  basketModal.style.display = "none";
});
