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

    const existingItem = basket.find((item) => item.name === name);
    if (existingItem) {
      existingItem.quantity += 1;
    } else {
      basket.push({
        name,
        price,
        quantity: 1,
      });
    }

    const totalQuantity = basket.reduce((sum, item) => sum + item.quantity, 0);
    basketCount.textContent = totalQuantity;
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
  basket.forEach((item, index) => {
    const li = document.createElement("li");
    li.innerHTML = `
    ${item.name} - €${item.price.toFixed(2)} (${item.quantity}x)
    <button class="remove-btn" data-index="${index}">✖</button>
  `;
    basketList.appendChild(li);
    total += item.price * item.quantity;
  });

  basketTotal.textContent = "Συνολικό ποσό: €" + total.toFixed(2);
  basketModal.style.display = "block";

  document.querySelectorAll(".remove-btn").forEach((btn) => {
    btn.addEventListener("click", function () {
      const index = parseInt(btn.getAttribute("data-index"));
      if (basket[index].quantity > 1) {
        basket[index].quantity -= 1;
      } else {
        basket.splice(index, 1);
      }
      basketCount.textContent = basket.reduce(
        (sum, item) => sum + item.quantity,
        0
      );
      basketIcon.click(); // ξαναφορτώνει το modal
    });
  });
});

closeBasket.addEventListener("click", function () {
  basketModal.style.display = "none";
});

// SEARCH BAR
const searchInput = document.getElementById("product-search");
const productCards = document.querySelectorAll(".product-card");

searchInput.addEventListener("input", function () {
  const query = searchInput.value.toLowerCase();

  productCards.forEach((card) => {
    const name = card.querySelector(".product-name").textContent.toLowerCase();
    const desc = card.querySelector(".product-desc").textContent.toLowerCase();

    const match = name.includes(query) || desc.includes(query);

    card.style.display = match ? "block" : "none";
  });
});
