import './bootstrap.js';
import './star-rating.js';
import './star-rating.min.js';

import './styles/app.scss';
import './styles/star-rating.css';

console.log('This log comes from assets/review.js - welcome to Review 🎉');

document.addEventListener('DOMContentLoaded', function() {
    // Écouter le clic sur le bouton "Laisser un avis"
    document.getElementById('showReviewFormBtn').addEventListener('click', function() {
        // Afficher le formulaire
        document.getElementById('reviewFormContainer').style.display = 'block';
    });
  
    // Écouter la soumission du formulaire
    document.getElementById('reviewForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Empêcher la soumission du formulaire
  
        // Récupérer les données du formulaire
        var formData = new FormData(this);
  
        // Envoyer les données via une requête AJAX
        fetch('{{ path("app_homepage") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            // Traiter la réponse
            console.log(data);
            // Par exemple, afficher un message de succès ou recharger la page
        })
        .catch(error => {
            console.error('Erreur lors de la soumission du formulaire:', error);
        });
    });
});
