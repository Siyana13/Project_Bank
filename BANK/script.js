function addNewSection(content) {
    var newSection = document.createElement('section');
    newSection.innerHTML = '<div class="invalid-data">'+content+'</div>';
    document.getElementById('nav_guests').insertAdjacentElement('afterend',newSection);
}