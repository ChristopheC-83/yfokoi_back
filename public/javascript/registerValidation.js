console.log("registerValidation.js chargé");
import { showAlert } from "./utilities.js";

document.addEventListener("DOMContentLoaded", () => {
  const registerForm = document.getElementById("registerForm");
  const name = document.getElementById("name");
  const email = document.getElementById("email");
  const password = document.getElementById("password");
  const confirmPassword = document.getElementById("confirmPassword");

  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

  if (registerForm) {
    registerForm.addEventListener("submit", (event) => {
      let valid = true;

      // Nettoyage des anciens messages
      const alertBox = document.getElementById("formAlert");
      if (alertBox) alertBox.classList.add("d-none");

      if (name.value.trim() === "") {
        showAlert("Le nom est requis");
        valid = false;
      } else if (email.value.trim() === "") {
        showAlert("L'email est requis");
        valid = false;
      } else if (!emailRegex.test(email.value)) {
        showAlert("Format d'email invalide");
        valid = false;
      } else if (password.value.trim() === "") {
        showAlert("Le mot de passe est requis");
        valid = false;
      } else if (password.value.length < 6) {
        showAlert("Le mot de passe doit contenir au moins 6 caractères");
        valid = false;
      } else if (confirmPassword.value.trim() === "") {
        showAlert("La confirmation du mot de passe est requise");
        valid = false;
      } else if (confirmPassword.value !== password.value) {
        showAlert("Les mots de passe ne correspondent pas");
        valid = false;
      }

      if (!valid) {
        event.preventDefault();
      }
    });
  }
});
