<script>
  //Navbar Settings
  const navbar = document.querySelector('.navbar');

  window.addEventListener('scroll', () => {
    if (window.scrollY > navbar.offsetHeight) {
      navbar.classList.add('navbar-scroll');
    } else {
      navbar.classList.remove('navbar-scroll');
    }
  });

</script>