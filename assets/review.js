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

        var formData = new FormData(event.target);
        var submitUrl = document.getElementById('reviewForm').getAttribute('data-submit-url');
        
        console.log(Object.fromEntries(formData.entries())); // Juste pour le debug

        fetch(submitUrl, {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log(data);
            // Traiter la réponse
            // Afficher le message de confirmation
            document.getElementById('reviewSuccessMessage').innerText = 'Merci d\'avoir laissé un avis';
            document.getElementById('reviewSuccessMessage').style.display = 'block';
            // Cacher le formulaire après la soumission
            document.getElementById('reviewFormContainer').style.display = 'none';
        })
        .catch(error => {
            console.error('Erreur lors de la soumission du formulaire:', error);
        });
    });
});
