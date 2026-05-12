</div><!-- end page-body -->
</div><!-- end main-content -->

<script>
document.addEventListener('click', function(e) {
  const sidebar = document.getElementById('sidebar');
  if (sidebar && sidebar.classList.contains('open')) {
    if (!sidebar.contains(e.target) && !e.target.closest('.topbar-toggle')) {
      sidebar.classList.remove('open');
    }
  }
});
document.querySelectorAll('.alert').forEach(function(el) {
  setTimeout(function() {
    el.style.transition = 'opacity 0.5s';
    el.style.opacity = '0';
    setTimeout(function() { el.remove(); }, 500);
  }, 4000);
});
</script>
</body>
</html>