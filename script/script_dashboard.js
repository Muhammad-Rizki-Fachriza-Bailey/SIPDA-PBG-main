document.getElementById('menu-icon').addEventListener('click', function() {
    document.getElementById('sidebar').classList.toggle('hide');
    document.querySelector('.navbar').classList.toggle('fullwidth');
    document.querySelector('.main-content').classList.toggle('fullwidth');
});
