document.addEventListener("DOMContentLoaded", function () {
  const STORAGE_KEY = "terra_basket_v1";

  // DOM elements (may be missing on some pages)
  const basketIcon = document.getElementById("basket-icon");
  const basketModal = document.getElementById("basket-modal");
  const basketList = document.getElementById("basket-list");
  const basketTotal = document.getElementById("basket-total");
  const basketCountEl = document.getElementById("basket-count");
  const closeBasket = document.getElementById("close-basket");
  const buyButtons = document.querySelectorAll(".add-to-cart-btn");
  const productCards = document.querySelectorAll(".product-card");
  const searchInput = document.getElementById("product-search");
  const buyBtnModal = document.getElementById("buy-btn-modal");

  // load/save helpers
  function loadBasket() {
    try {
      const raw = localStorage.getItem(STORAGE_KEY);
      return raw ? JSON.parse(raw) : [];
    } catch (e) {
      console.error("Failed to load basket", e);
      return [];
    }
  }

  function saveBasket(basket) {
    try {
      localStorage.setItem(STORAGE_KEY, JSON.stringify(basket));
    } catch (e) {
      console.error("Failed to save basket", e);
    }
  }

  function updateCount(basket) {
    if (!basketCountEl) return;
    const totalQuantity = basket.reduce(
      (sum, item) => sum + (item.quantity || 0),
      0
    );
    basketCountEl.textContent = totalQuantity;
  }

  // persisted basket
  const basket = loadBasket();
  updateCount(basket);

  // add to basket handlers
  if (buyButtons.length) {
    buyButtons.forEach((btn) => {
      btn.addEventListener("click", function () {
        const card = btn.closest(".product-card");
        if (!card) return;
        const name =
          card.getAttribute("data-name") ||
          (card.querySelector(".product-name") &&
            card.querySelector(".product-name").textContent) ||
          null;
        const priceRaw =
          card.getAttribute("data-price") ||
          (card.querySelector(".product-price") &&
            card
              .querySelector(".product-price")
              .textContent.replace("€", "")) ||
          null;
        const price = priceRaw ? parseFloat(priceRaw) : NaN;
        if (!name || isNaN(price)) {
          console.warn("Invalid product data, skipping add", {
            name,
            priceRaw,
          });
          return;
        }

        const existingItem = basket.find((item) => item.name === name);
        if (existingItem) {
          existingItem.quantity = (existingItem.quantity || 0) + 1;
        } else {
          basket.push({ name, price, quantity: 1 });
        }

        saveBasket(basket);
        updateCount(basket);
      });
    });
  }

  // open modal and render basket
  if (basketIcon && basketModal && basketList && basketTotal) {
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

      // remove handlers
      document.querySelectorAll(".remove-btn").forEach((btn) => {
        btn.addEventListener("click", function () {
          const index = parseInt(btn.getAttribute("data-index"), 10);
          if (isNaN(index)) return;
          if (basket[index].quantity > 1) {
            basket[index].quantity -= 1;
          } else {
            basket.splice(index, 1);
          }
          saveBasket(basket);
          updateCount(basket);
          // re-open to refresh content
          basketIcon.click();
        });
      });
    });
  }

  // close modal
  if (closeBasket && basketModal) {
    closeBasket.addEventListener("click", function () {
      basketModal.style.display = "none";
    });
  }

  // Add this HTML for guest checkout modal
  function createGuestCheckoutModal() {
    const modal = document.createElement("div");
    modal.id = "guest-checkout-modal";
    modal.innerHTML = `
        <div class="modal-content">
            <h3>Στοιχεία Αποστολής</h3>
            <form id="guest-checkout-form">
                <input type="text" name="first_name" placeholder="Όνομα" required>
                <input type="text" name="last_name" placeholder="Επώνυμο" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="text" name="address" placeholder="Διεύθυνση" required>
                <input type="text" name="place" placeholder="Πόλη" required>
                <input type="text" name="zip" placeholder="T.K." required>
                <button type="submit">Ολοκλήρωση Παραγγελίας</button>
                <button type="button" class="close-modal">Ακύρωση</button>
            </form>
        </div>
    `;
    return modal;
  }

  // submit order (if modal button exists)
  if (buyBtnModal) {
    buyBtnModal.addEventListener("click", function () {
      if (basket.length === 0) return;

      if (!terraAjax.isLoggedIn) {
        // Show guest checkout modal
        const guestModal = createGuestCheckoutModal();
        document.body.appendChild(guestModal);
        guestModal.style.display = "block";

        // Handle guest form submission
        const form = guestModal.querySelector("form");
        form.addEventListener("submit", function (e) {
          e.preventDefault();
          const formData = new FormData(form);
          const shippingInfo = Object.fromEntries(formData);
          submitOrder(shippingInfo);
          guestModal.remove();
        });

        // Handle close button
        guestModal
          .querySelector(".close-modal")
          .addEventListener("click", () => {
            guestModal.remove();
          });
      } else {
        // Logged in user - submit directly
        submitOrder();
      }
    });
  }

  // Separate function for order submission
  function submitOrder(shippingInfo = null) {
    const formData = new FormData();
    formData.append("action", "submit_order");
    formData.append("_ajax_nonce", terraAjax.nonce);
    formData.append("order_data", JSON.stringify(basket));

    if (shippingInfo) {
      formData.append("shipping_info", JSON.stringify(shippingInfo));
    }

    fetch(terraAjax.ajaxurl, {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          alert("Η παραγγελία καταχωρήθηκε!");
          basket.length = 0;
          saveBasket(basket);
          updateCount(basket);
          if (basketModal) basketModal.style.display = "none";
        } else {
          throw new Error(data.data);
        }
      })
      .catch((error) => {
        console.error("Order submission error:", error);
        alert("Σφάλμα κατά την καταχώρηση της παραγγελίας: " + error.message);
      });
  }

  // SEARCH BAR (guarded)
  if (searchInput && productCards.length) {
    searchInput.addEventListener("input", function () {
      const query = searchInput.value.toLowerCase();
      productCards.forEach((card) => {
        const nameEl = card.querySelector(".product-name");
        const descEl = card.querySelector(".product-desc");
        const name = nameEl ? nameEl.textContent.toLowerCase() : "";
        const desc = descEl ? descEl.textContent.toLowerCase() : "";
        const match = name.includes(query) || desc.includes(query);
        card.style.display = match ? "block" : "none";
      });
    });
  }
});
