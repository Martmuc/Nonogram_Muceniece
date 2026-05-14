// js/modals.js

// Fonction permettant d'afficher une fenêtre modale Bootstrap
// Elle est appelée depuis le PHP (par exemple dans index.php)
function showModalIfExist(modalId) {
    // Récupère l'élément HTML correspondant à l'ID donné
    var modalElement = document.getElementById(modalId);

    // Vérifie si l'élément existe dans la page
    if (modalElement) {
        // Crée une instance de modal Bootstrap
        var modalInstance = new bootstrap.Modal(modalElement);

        // Affiche la fenêtre modale
        modalInstance.show();
    }
}

// Code exécuté une fois que le DOM est entièrement chargé
document.addEventListener("DOMContentLoaded", function () {

    // Sélectionne toutes les icônes permettant d'afficher/masquer le mot de passe
    var eyes = document.querySelectorAll(".toggle-password");

    // Parcourt chaque icône
    eyes.forEach(function (eye) {

        // Ajoute un événement au clic sur l'icône
        eye.addEventListener("click", function () {

            // Récupère l'ID de l'input associé (stocké dans l'attribut data-target)
            var inputId = this.getAttribute("data-target");

            // Récupère le champ input correspondant
            var input = document.getElementById(inputId);

            // Vérifie que le champ existe
            if (input) {

                // Si le champ est de type "password"
                if (input.type === "password") {

                    // Affiche le mot de passe en clair
                    input.type = "text";

                    // Change l'icône (œil ouvert)
                    this.src = "../extras/oeil_ouvert_petit.png";

                } else {
                    // Cache le mot de passe
                    input.type = "password";

                    // Change l'icône (œil fermé)
                    this.src = "../extras/oeil_ferme_petit.png";
                }
            }
        });
    });
});