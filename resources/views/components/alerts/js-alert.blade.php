@if (session()->has('alert-success'))
    <script>
        toastr.success("{{ session('alert-success') }}")
    </script>
@elseif(session()->has('alert-info'))
    <script>
        toastr.info("{{ session('alert-info') }}")
    </script>
@elseif(session()->has('alert-warning'))
    <script>
        toastr.warning("{{ session('alert-warning') }}")
    </script>
@elseif(session()->has('alert-error'))
    <script>
        toastr.error("{{ session('alert-error') }}")
    </script>
@endif
