// js/modals.js

// Fonction pour ouvrir les modals (Appelée par le PHP de l'index)
function showModalIfExist(modalId) {
    var modalElement = document.getElementById(modalId);
    if (modalElement) {
        var modalInstance = new bootstrap.Modal(modalElement);
        modalInstance.show();
    }
}

// Gestion des icônes "œil" pour les mots de passe
document.addEventListener("DOMContentLoaded", function () {
    var eyes = document.querySelectorAll(".toggle-password");

    eyes.forEach(function (eye) {
        eye.addEventListener("click", function () {
            var inputId = this.getAttribute("data-target");
            var input = document.getElementById(inputId);

            if (input) {
                if (input.type === "password") {
                    input.type = "text";
                    this.src = "../extras/oeil_ouvert_petit.png";
                } else {
                    input.type = "password";
                    this.src = "../extras/oeil_ferme_petit.png";
                }
            }
        });
    });
});