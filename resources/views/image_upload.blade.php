<!DOCTYPE html>
<html>
<head>
    <title>Add Watermark</title>
</head>
<body>
     <form action="{{ url('/add_watermark') }}" method="post" enctype="multipart/form-data">
          @csrf
          <label for="image">Choose an image:</label>
          <input type="file" id="image" name="image"><br><br>
          <!-- <label for="watermark">Watermark for image:</label>
          <input type="text" id="watermark" name="watermark"><br><br> -->
          <label for="watermark">Watermark for image:</label>
          <input type="file" id="watermark" name="watermark"><br><br>
          <button type="submit">Upload</button>
     </form>
</body>
</html>
