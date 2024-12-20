const options = document.querySelectorAll('select option');
options.forEach(option => {
  if (option.textContent.length > 90) { // Stel hier de maximale lengte in
    option.textContent = option.textContent.substring(0, 60) + '...';
  }
});