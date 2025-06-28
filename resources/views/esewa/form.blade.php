<!DOCTYPE html>
<html>

<head>
  <title>Redirecting to eSewa...</title>
</head>

<body>
  <form id="esewa_form" name="esewa_form" action="{{ $process_url }}" method="POST">
    @foreach($data as $key => $value)
    <input type="hidden" name="{{ $key }}" value="{{ $value }}" required>
    @endforeach
  </form>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('esewa_form').submit();
    });
  </script>
</body>

</html>