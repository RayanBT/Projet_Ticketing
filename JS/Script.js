const container = document.getElementById('container');
const registerBtn = document.getElementById('register');
const loginBtn = document.getElementById('connexion');

registerBtn.addEventListener('click', () => {
    container.classList.add("active");
});
loginBtn.addEventListener('click', () => {
    container.classList.remove("active");
});

// Fonction pour créer et afficher le volet
function afficherVolet(message, couleur) {
    const volet = document.createElement("div");
    volet.className = "volet";

    // Ajoute une classe spécifique pour la couleur
    volet.classList.add(couleur);

    volet.innerHTML = message;
    document.body.appendChild(volet);

    // Ajout de la classe "show" pour afficher le volet
    volet.classList.add("show");

    // Supprime le volet après quelques secondes (ajustez le délai selon vos préférences)
    setTimeout(function () {
        document.body.removeChild(volet);
    }, 3000); // 3000 millisecondes (3 secondes)
}
