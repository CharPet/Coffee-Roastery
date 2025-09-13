document.addEventListener("DOMContentLoaded", function () {
  const qs = (sel) => document.querySelector(sel);
  const qsa = (sel) => document.querySelectorAll(sel);

  // Initialize basket
  let basket = [];
  const STORAGE_KEY = "terra_basket_v1";

  function loadBasket() {
    try {
      const stored = localStorage.getItem(STORAGE_KEY);
      basket = stored ? JSON.parse(stored) : [];
    } catch (e) {
      console.warn("Failed to load basket:", e);
      basket = [];
    }
    return basket;
  }

  function saveBasket() {
    try {
      localStorage.setItem(STORAGE_KEY, JSON.stringify(basket));
    } catch (e) {
      console.warn("Failed to save basket:", e);
    }
  }

  // Load basket on page load
  loadBasket();

  // Buy button handler
  const buyBtn = qs("#buy-btn-modal");
  if (buyBtn) {
    buyBtn.addEventListener("click", function (e) {
      e.preventDefault();
      console.log("Buy button clicked, basket:", basket);

      if (!basket || basket.length === 0) {
        alert("Το καλάθι είναι άδειο");
        return;
      }

      // Check if user is logged in
      const isLoggedIn =
        typeof terraAjax !== "undefined" && terraAjax.isLoggedIn;
      console.log("User logged in:", isLoggedIn);

      if (isLoggedIn) {
        // Submit order directly
        submitOrder();
      } else {
        // Show guest checkout
        openGuestCheckout();
      }
    });
  }

  function submitOrder(shippingInfo = null) {
    console.log("Submitting order...", { basket, shippingInfo, terraAjax });

    if (typeof terraAjax === "undefined") {
      console.error("terraAjax not available");
      alert("Σφάλμα: Δεν είναι δυνατή η επικοινωνία με τον διακομιστή");
      return;
    }

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
        console.log("Order response:", data);

        if (data.success) {
          alert("Η παραγγελία καταχωρήθηκε επιτυχώς!");

          // Clear basket
          basket.length = 0;
          saveBasket();

          // Update UI
          const basketCount = qs("#basket-count");
          if (basketCount) basketCount.textContent = "0";

          // Close modals
          const basketModal = qs("#basket-modal");
          const guestModal = qs("#guest-checkout-modal");
          const overlay = qs("#modal-overlay");

          if (basketModal) basketModal.style.display = "none";
          if (guestModal) guestModal.style.display = "none";
          if (overlay) overlay.classList.remove("active");
        } else {
          alert("Σφάλμα: " + (data.data || "Άγνωστο σφάλμα"));
        }
      })
      .catch((error) => {
        console.error("Order error:", error);
        alert("Σφάλμα κατά την υποβολή της παραγγελίας");
      });
  }

  function openGuestCheckout() {
    let modal = qs("#guest-checkout-modal");
    if (!modal) {
      modal = createGuestCheckoutModal();
      document.body.appendChild(modal);
    }

    const overlay = ensureOverlay();
    overlay.classList.add("active");
    modal.style.display = "flex";

    // Lock scroll
    lockScroll();
  }

  function closeGuestCheckout() {
    const modal = document.getElementById("guest-checkout-modal");
    if (modal) {
      modal.style.display = "none";
    }
    unlockScroll();
  }

  function createGuestCheckoutModal() {
    const modal = document.createElement("div");
    modal.id = "guest-checkout-modal";
    modal.className = "modal-overlay";
    modal.innerHTML = `
      <div class="modal-content">
        <h3>Στοιχεία Παραλαβής</h3>
        <form id="guest-checkout-form" class="signup-form">
          <div class="form-row">
            <div class="input-group">
              <label for="guest-name">Όνομα:</label>
              <input type="text" id="guest-name" name="name" required>
            </div>
            <div class="input-group">
              <label for="guest-email">Email:</label>
              <input type="email" id="guest-email" name="email" required>
            </div>
          </div>
          
          <div class="form-row">
            <div class="input-group">
              <label for="guest-phone">Τηλέφωνο:</label>
              <input type="tel" id="guest-phone" name="phone" required>
            </div>
            <div class="input-group">
              <label for="guest-place">Τόπος:</label>
              <input type="text" id="guest-place" name="place" required>
            </div>
          </div>
          
          <div class="form-row">
            <div class="input-group">
              <label for="guest-zip">Τ.Κ:</label>
              <input type="text" id="guest-zip" name="zip" required>
            </div>
            <div class="input-group">
              <label for="guest-address">Διεύθυνση:</label>
              <input type="text" id="guest-address" name="address" required>
            </div>
          </div>
          
          <div class="form-actions">
            <button type="submit" class="submit-btn">Ολοκλήρωση Παραγγελίας</button>
            <button type="button" class="close-modal cancel-btn">Ακύρωση</button>
          </div>
        </form>
      </div>
    `;

    // Add form submit handler
    const form = modal.querySelector("#guest-checkout-form");
    form.addEventListener("submit", function (e) {
      e.preventDefault();
      const formData = new FormData(form);
      const shippingInfo = Object.fromEntries(formData);
      console.log("Guest shipping info:", shippingInfo);
      submitOrder(shippingInfo);
    });

    // Add close handler
    const closeBtn = modal.querySelector(".close-modal");
    closeBtn.addEventListener("click", function () {
      closeGuestCheckout();
    });

    // Close on overlay click
    modal.addEventListener("click", function (e) {
      if (e.target === modal) {
        closeGuestCheckout();
      }
    });

    return modal;
  }

  function ensureOverlay() {
    let overlay = qs("#modal-overlay");
    if (!overlay) {
      overlay = document.createElement("div");
      overlay.id = "modal-overlay";
      overlay.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        z-index: 1999;
        display: none;
      `;
      document.body.appendChild(overlay);
    }
    return overlay;
  }

  // START: SCROLL LOCK LOGIC

  function lockScroll() {
    // If already locked, do nothing
    if (document.body.classList.contains("modal-open")) return;

    // Save the current scroll position
    const scrollY = window.scrollY;
    document.body.dataset.scrollY = scrollY;

    document.body.style.position = "fixed";
    document.body.style.width = "100%";
    document.body.style.top = `-${scrollY}px`;
    document.body.classList.add("modal-open");
  }

  function unlockScroll() {
    // If not locked, do nothing
    if (!document.body.classList.contains("modal-open")) return;

    // Retrieve the saved scroll position
    const scrollY = document.body.dataset.scrollY || "0";

    // Remove locking styles
    document.body.style.position = "";
    document.body.style.width = "";
    document.body.style.top = "";
    document.body.classList.remove("modal-open");

    // Restore the original scroll position
    window.scrollTo(0, parseInt(scrollY));
  }

  // BASKET
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

  // MOBILE NAV
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

  // TESTIMONIALS
  function initTestimonials() {
    const testimonials = qsa(".testimonial");
    const dots = qsa(".dot");

    if (!testimonials.length) {
      console.warn(
        "No testimonials found - make sure elements have class 'testimonial'"
      );
      return;
    }

    let slideIndex = 0; // Start from 0 for array indexing

    // Initialize - hide all testimonials first
    testimonials.forEach((testimonial, index) => {
      testimonial.style.display = "none";
      testimonial.classList.remove("active");
    });

    // Show first testimonial
    if (testimonials[0]) {
      testimonials[0].style.display = "block";
      testimonials[0].classList.add("active");
    }

    // Update dots if they exist
    if (dots.length) {
      dots.forEach((dot, index) => {
        dot.classList.toggle("active", index === 0);
      });
    }

    function showSlides() {
      // Hide current testimonial
      if (testimonials[slideIndex]) {
        testimonials[slideIndex].style.display = "none";
        testimonials[slideIndex].classList.remove("active");
      }

      // Move to next slide
      slideIndex = (slideIndex + 1) % testimonials.length;

      // Show new testimonial
      if (testimonials[slideIndex]) {
        testimonials[slideIndex].style.display = "block";
        testimonials[slideIndex].classList.add("active");
      }

      // Update dots
      if (dots.length) {
        dots.forEach((dot, index) => {
          dot.classList.toggle("active", index === slideIndex);
        });
      }

      console.log(
        `Showing testimonial ${slideIndex + 1} of ${testimonials.length}`
      );
    }

    // Auto-rotate every 5 seconds
    const testimonialInterval = setInterval(showSlides, 5000);

    // Add click handlers for dots if they exist
    dots.forEach((dot, index) => {
      dot.addEventListener("click", function () {
        clearInterval(testimonialInterval); // Stop auto-rotation temporarily

        // Hide current
        if (testimonials[slideIndex]) {
          testimonials[slideIndex].style.display = "none";
          testimonials[slideIndex].classList.remove("active");
        }

        // Show clicked
        slideIndex = index;
        if (testimonials[slideIndex]) {
          testimonials[slideIndex].style.display = "block";
          testimonials[slideIndex].classList.add("active");
        }

        // Update dots
        dots.forEach((d, i) => {
          d.classList.toggle("active", i === index);
        });

        // Restart auto-rotation after 10 seconds
        setTimeout(() => {
          setInterval(showSlides, 5000);
        }, 10000);
      });
    });
  }

  //  PASSWORD VALIDATION
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

  // MODAL OVERLAY
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
  // initCheckout();
  initSearch();
  initMobileNav();
  initTestimonials();
  initPasswordValidation();
});
