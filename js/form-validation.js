document.addEventListener("DOMContentLoaded", function () {
  // --- REGEX DEFINITIONS ---
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  // Requires: 8+ characters, 1 uppercase, 1 lowercase, 1 number, 1 special character.
  const strongPasswordRegex =
    /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
  // Requires: 4-20 characters, letters, numbers, and underscores only.
  const usernameRegex = /^[a-zA-Z0-9_]{4,20}$/;

  // --- HELPER FUNCTIONS ---
  function getErrorElement(input) {
    // Assumes the error span is the next sibling element.
    return input.nextElementSibling;
  }

  function showError(input, message) {
    const errorElement = getErrorElement(input);
    if (errorElement && errorElement.classList.contains("validation-error")) {
      errorElement.textContent = message;
      errorElement.style.display = "block";
    }
    input.classList.add("invalid");
  }

  function hideError(input) {
    const errorElement = getErrorElement(input);
    if (errorElement && errorElement.classList.contains("validation-error")) {
      errorElement.textContent = "";
      errorElement.style.display = "none";
    }
    input.classList.remove("invalid");
  }

  // --- VALIDATION LOGIC ---

  // 1. Newsletter Form
  const newsletterEmail = document.querySelector("#newsletter-email");
  if (newsletterEmail) {
    newsletterEmail.addEventListener("input", () => {
      if (emailRegex.test(newsletterEmail.value)) {
        hideError(newsletterEmail);
      } else {
        showError(newsletterEmail, "Please enter a valid email address.");
      }
    });
  }

  // 2. Contact Form (.page-id-44 form)
  const contactForm = document.querySelector(".page-id-44 form");
  if (contactForm) {
    const emailInput = contactForm.querySelector('input[type="email"]');
    if (emailInput) {
      emailInput.addEventListener("input", () => {
        if (emailRegex.test(emailInput.value)) {
          hideError(emailInput);
        } else {
          showError(emailInput, "A valid email is required.");
        }
      });
    }
  }

  // 3. Custom Login Form
  const loginForm = document.querySelector("#custom-loginform");
  if (loginForm) {
    const userInput = loginForm.querySelector("#user_login");
    if (userInput) {
      userInput.addEventListener("input", () => {
        if (userInput.value.length > 0) {
          hideError(userInput);
        } else {
          showError(userInput, "Username or Email is required.");
        }
      });
    }
  }

  // 4. Signup Form
  const signupForm = document.querySelector("#signup-form");
  if (signupForm) {
    const username = signupForm.querySelector('input[name="username"]');
    const email = signupForm.querySelector('input[name="email"]');
    const password = signupForm.querySelector('input[name="password"]');
    const confirmPassword = signupForm.querySelector(
      'input[name="confirm_password"]'
    );

    if (username) {
      username.addEventListener("input", () => {
        if (usernameRegex.test(username.value)) {
          hideError(username);
        } else {
          showError(
            username,
            "Username must be 4-20 characters (letters, numbers, _)."
          );
        }
      });
    }

    if (email) {
      email.addEventListener("input", () => {
        if (emailRegex.test(email.value)) {
          hideError(email);
        } else {
          showError(email, "Please enter a valid email address.");
        }
      });
    }

    if (password) {
      password.addEventListener("input", () => {
        if (strongPasswordRegex.test(password.value)) {
          hideError(password);
        } else {
          showError(
            password,
            "Password must be 8+ characters with uppercase, lowercase, number, and special symbol."
          );
        }
        // Also check the confirmation field when the main password changes
        if (confirmPassword && confirmPassword.value.length > 0) {
          if (password.value === confirmPassword.value) {
            hideError(confirmPassword);
          } else {
            showError(confirmPassword, "Passwords do not match.");
          }
        }
      });
    }

    if (confirmPassword) {
      confirmPassword.addEventListener("input", () => {
        if (password && password.value === confirmPassword.value) {
          hideError(confirmPassword);
        } else {
          showError(confirmPassword, "Passwords do not match.");
        }
      });
    }
  }
});
