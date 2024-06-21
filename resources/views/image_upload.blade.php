<!DOCTYPE html>
<html>
<head>
    <title>Add Watermark</title>
</head>
<body>
     <form action="{{ url('/add_watermark') }}" method="post" enctype="multipart/form-data">
          @csrf
          <label for="image">Choose an image:</label>
          <input type="file" id="image" name="image" required><br><br>
          <label for="overlay">Choose an overlay image:</label>
          <input type="file" id="overlay" name="overlay" required><br><br>
          <label for="x_position">X Position:</label>
          <input type="number" id="x_position" name="x_position" required><br><br>
          <label for="y_position">Y Position:</label>
          <input type="number" id="y_position" name="y_position" required><br><br>
          <button type="submit">Upload and Overlay</button>
     </form>
</body>
</html>
