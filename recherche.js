const closeSearchButton = document.getElementById('close-search');
const container = document.querySelector('.container');
const searchForm = document.querySelector('.search-form');

closeSearchButton.addEventListener('click', () => {
    container.style.display = 'none';
    searchForm.reset(); // Réinitialiser le formulaire de recherche
});