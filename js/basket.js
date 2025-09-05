document.addEventListener("DOMContentLoaded", function () {
  const qs = (sel) => document.querySelector(sel);
  const qsa = (sel) => document.querySelectorAll(sel);
  const STORAGE_KEY = "terra_basket_v1";

  // ---------------- BASKET ----------------
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

  function updateCount(basket, basketCountEl) {
    if (!basketCountEl) return;
    const totalQuantity = basket.reduce(
      (sum, item) => sum + (item.quantity || 0),
      0
    );
    basketCountEl.textContent = totalQuantity;
  }

  function initBasket() {
    const basketIcon = qs("#basket-icon");
    const basketModal = qs("#basket-modal");
    const basketList = qs("#basket-list");
    const basketTotal = qs("#basket-total");
    const basketCountEl = qs("#basket-count");
    const closeBasket = qs("#close-basket");
    const buyButtons = qsa(".add-to-cart-btn");

    const basket = loadBasket();
    updateCount(basket, basketCountEl);

    // add to basket
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
          updateCount(basket, basketCountEl);
        });
      });
    }

    // open basket modal
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
        // basketModal.style.display = 'block';
        showModal(basketModal);

        // remove handlers
        basketList.querySelectorAll(".remove-btn").forEach((btn) => {
          btn.addEventListener("click", function () {
            const index = parseInt(btn.getAttribute("data-index"), 10);
            if (isNaN(index)) return;
            if (basket[index].quantity > 1) {
              basket[index].quantity -= 1;
            } else {
              basket.splice(index, 1);
            }
            saveBasket(basket);
            updateCount(basket, basketCountEl);
            basketIcon.click();
          });
        });
      });
    }

    // close modal
    if (closeBasket && basketModal) {
      closeBasket.addEventListener("click", function () {
        // basketModal.style.display = 'none';
        hideModal(basketModal);
      });
    }
  }

  // ---------------- CHECKOUT ----------------
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

  function submitOrder(basket, basketModal, shippingInfo = null) {
    const formData = new FormData();
    formData.append("action", "submit_order");
    formData.append("_ajax_nonce", terraAjax.nonce);
    formData.append("order_data", JSON.stringify(basket));
    if (shippingInfo) {
      formData.append("shipping_info", JSON.stringify(shippingInfo));
    }

    fetch(terraAjax.ajaxurl, { method: "POST", body: formData })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          alert("Η παραγγελία καταχωρήθηκε!");
          basket.length = 0;
          saveBasket(basket);
          updateCount(basket, qs("#basket-count"));
          if (basketModal) basketModal.style.display = "none";
        } else {
          throw new Error(data.data);
        }
      })
      .catch((error) => {
        console.error("Order submission error:", error);
        alert("Σφάλμα κατά την καταχώρηση: " + error.message);
      });
  }

  function initCheckout() {
    const buyBtnModal = qs("#buy-btn-modal");
    const basketModal = qs("#basket-modal");
    const basket = loadBasket();

    if (!buyBtnModal) return;

    buyBtnModal.addEventListener("click", function () {
      if (basket.length === 0) return;

      if (!terraAjax.isLoggedIn) {
        const guestModal = createGuestCheckoutModal();
        document.body.appendChild(guestModal);
        guestModal.style.display = "block";

        const form = guestModal.querySelector("form");
        form.addEventListener("submit", function (e) {
          e.preventDefault();
          const formData = new FormData(form);
          const shippingInfo = Object.fromEntries(formData);
          submitOrder(basket, basketModal, shippingInfo);
          guestModal.remove();
        });

        guestModal
          .querySelector(".close-modal")
          .addEventListener("click", () => guestModal.remove());
      } else {
        submitOrder(basket, basketModal);
      }
    });
  }

  // ---------------- SEARCH ----------------
  function initSearch() {
    const searchInput = qs("#product-search");
    const productCards = qsa(".product-card");
    if (!searchInput || !productCards.length) return;

    searchInput.addEventListener("input", function () {
      const query = searchInput.value.toLowerCase();
      productCards.forEach((card) => {
        const nameEl = card.querySelector(".product-name");
        const descEl = card.querySelector(".product-desc");
        const name = nameEl ? nameEl.textContent.toLowerCase() : "";
        const desc = descEl ? descEl.textContent.toLowerCase() : "";
        card.style.display =
          name.includes(query) || desc.includes(query) ? "block" : "none";
      });
    });
  }

  // ---------------- MOBILE NAV ----------------
  function initMobileNav() {
    const mobileToggle = qs(".mobile-nav-toggle");
    const mobileNav = qs(".mobile-nav");
    const hamburger = qs(".hamburger");
    if (!mobileNav) return;

    let navOverlay = qs(".nav-overlay");
    if (!navOverlay) {
      navOverlay = document.createElement("div");
      navOverlay.className = "nav-overlay";
      document.body.appendChild(navOverlay);
    }

    function toggleMenu() {
      const isActive = mobileNav.classList.contains("active");
      if (isActive) {
        mobileNav.classList.remove("active");
        navOverlay.classList.remove("active");
        if (hamburger) hamburger.classList.remove("active");
        document.body.style.overflow = "";
      } else {
        mobileNav.classList.add("active");
        navOverlay.classList.add("active");
        if (hamburger) hamburger.classList.add("active");
        document.body.style.overflow = "hidden";
      }
    }

    if (mobileToggle) mobileToggle.addEventListener("click", toggleMenu);
    navOverlay.addEventListener("click", toggleMenu);

    qsa(".mobile-nav-links a").forEach((link) => {
      link.addEventListener("click", () => {
        if (mobileNav.classList.contains("active")) toggleMenu();
      });
    });

    document.addEventListener("keydown", (e) => {
      if (e.key === "Escape" && mobileNav.classList.contains("active")) {
        toggleMenu();
      }
    });

    window.addEventListener("resize", () => {
      if (window.innerWidth > 1024 && mobileNav.classList.contains("active")) {
        toggleMenu();
      }
    });
  }

  // ---------------- TESTIMONIALS ----------------
  function initTestimonials() {
    let slideIndex = 1;
    showSlides(slideIndex);

    setInterval(() => {
      slideIndex++;
      showSlides(slideIndex);
    }, 5000);

    function showSlides(n) {
      const slides = qsa(".testimonial");
      const dots = qsa(".dot");

      if (!slides.length) return;

      if (n > slides.length) slideIndex = 1;
      if (n < 1) slideIndex = slides.length;

      slides.forEach((s) => s.classList.remove("active"));
      dots.forEach((d) => d.classList.remove("active"));

      if (slides[slideIndex - 1])
        slides[slideIndex - 1].classList.add("active");
      if (dots[slideIndex - 1]) dots[slideIndex - 1].classList.add("active");
    }
  }

  // ---------------- PASSWORD VALIDATION ----------------
  function initPasswordValidation() {
    const password = qs("#password");
    const confirmPassword = qs("#confirm_password");

    if (password) {
      password.addEventListener("input", function () {
        if (this.value.length < 6) {
          this.style.borderColor = "#dc3545";
        } else if (this.value.length < 8) {
          this.style.borderColor = "#ffc107";
        } else {
          this.style.borderColor = "#28a745";
        }
      });
    }

    if (confirmPassword && password) {
      confirmPassword.addEventListener("input", function () {
        if (this.value === password.value && this.value.length > 0) {
          this.style.borderColor = "#28a745";
        } else if (this.value.length > 0) {
          this.style.borderColor = "#dc3545";
        }
      });
    }
  }

  // ---------------- MODAL OVERLAY ----------------
  // ensure overlay exists
  function ensureOverlay() {
    let overlay = document.getElementById("modal-overlay");
    if (!overlay) {
      overlay = document.createElement("div");
      overlay.id = "modal-overlay";
      document.body.appendChild(overlay);
    }
    return overlay;
  }

  // show modal helper
  function showModal(modalEl) {
    const overlay = ensureOverlay();
    overlay.classList.add("active");
    if (typeof modalEl.style !== "undefined") modalEl.style.display = "block";
    modalEl.setAttribute("aria-hidden", "false");
    // close modal when clicking overlay
    overlay.addEventListener("click", function onOverlayClick() {
      hideModal(modalEl);
      overlay.removeEventListener("click", onOverlayClick);
    });
  }

  // hide modal helper
  function hideModal(modalEl) {
    const overlay = document.getElementById("modal-overlay");
    if (overlay) overlay.classList.remove("active");
    if (typeof modalEl.style !== "undefined") modalEl.style.display = "none";
    modalEl.setAttribute("aria-hidden", "true");
  }

  // ---------------- INIT ALL ----------------
  initBasket();
  initCheckout();
  initSearch();
  initMobileNav();
  initTestimonials();
  initPasswordValidation();
});
