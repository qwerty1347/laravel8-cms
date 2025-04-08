<!-- CoreUI and necessary plugins-->
<script src="{{ asset('js/coreui.bundle.min.js') }}"></script>
<script src="{{ asset('js/simplebar.min.js') }}"></script>
<script>
  const header = document.querySelector('header.header');

  document.addEventListener('scroll', () => {
    if (header) {
      header.classList.toggle('shadow-sm', document.documentElement.scrollTop > 0);
    }
  });

</script>
<!-- Plugins and scripts required by this view-->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('js/coreui-chartjs.js') }}"></script>
<script src="{{ asset('js/coreui-utils.js') }}"></script>
<script src="{{ asset('js/main.js') }}"></script>
