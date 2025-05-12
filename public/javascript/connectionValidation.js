import { showAlert } from "./utilities.js";

document.addEventListener("DOMContentLoaded", () => {
  const connectionForm = document.getElementById("connectionForm");
  const name_email = document.getElementById("name_email");
  const password = document.getElementById("password");

  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

  if (connectionForm) {
    console.log("connexionValidation.js chargé");
    connectionForm.addEventListener("submit", (event) => {
      let valid = true;

      // Nettoyage des anciens messages
      const alertBox = document.getElementById("formAlert");
      if (alertBox) alertBox.classList.add("d-none");

      if (name_email.value.trim() === "") {
        showAlert("Le nom ou l'adresse email est requis");
        valid = false;
      } else if (password.value.trim() === "") {
        showAlert("Le mot de passe est requis");
        valid = false;
      }

      if (!valid) {
        event.preventDefault();
      }
    });
  }
});
