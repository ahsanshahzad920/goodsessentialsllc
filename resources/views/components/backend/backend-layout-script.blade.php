  <!-- JavaScript Libraries -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
  </script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
  <!-- Template Javascript -->
  <script src="{{ asset('dashasset/js/main.js') }}"></script>
  <script src="{{ asset('dashasset/js/graph.js') }}"></script>


  {{-- Error Disapear after some seconds --}}
  <script>
      $(document).ready(function() {
          setTimeout(function() {
              $('.alert').fadeOut('slow');
          }, 6000);
      });
  </script>


  {{-- Seweet Alert --}}
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  {{-- Show Success Message Alert --}}
  @if (session('success'))
      <script>
          Swal.fire({
              title: "Success",
              text: "{{ session('success') }}",
              icon: "success"
          });
      </script>
  @endif


  {{-- CK Editor --}}
  <script src="https://cdn.ckeditor.com/ckeditor5/41.4.1/classic/ckeditor.js"></script>
  <script>
      document.addEventListener("DOMContentLoaded", function() {
          var editors = document.querySelectorAll('textarea.editor');
          editors.forEach(function(editor) {
              ClassicEditor
                  .create(editor)
                  .catch(error => {
                      console.error(error);
                  });
          });
      });
  </script>
