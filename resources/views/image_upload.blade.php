<!DOCTYPE html>
<html>
<head>
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
     <title>Add Watermark</title>
     <style>
     .form-body {
          display: flex;
          justify-content: center;
          align-items: center;
          min-height: 50vh; /*vh stands for viewport height */
          margin-top: 4%;
          border: 1px solid black;
          /* width: 80%; */
     }

     form {
          width: 80%; /* adjust this value to control the form's width */
     }    
     
     .form-head h5{
          display: flex;
          justify-content: center;
          align-items: center;
     }


     </style>
</head>
<body>
     <div class="container">
          <!-- <div class="form-head mt-4">
               <h5>How to add watermark</h5>
          </div> -->
          <div class="form-body">
               <form action="{{ url('/add_watermark') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-content m-4">
                         <div class="col-lg-6 py-2">
                              <label for="image" class="form-label">Choose an image:</label>
                              <input type="file" id="image" name="image" class="form-control">
                         </div>
                         <div class="col-lg-6 py-2">
                              <label for="overlay" class="form-label">Choose an overlay image:</label>
                              <input type="file" id="overlay" name="overlay" class="form-control">
                         </div>
                         <div class="col-lg-6 py-2">
                              <label for="x_position" class="form-label">X Position:</label>
                              <input type="number" id="x_position" name="x_position" class="form-control">
                         </div>
                         <div class="col-lg-6 py-2">
                              <label for="y_position" class="form-label">Y Position:</label>
                              <input type="number" id="y_position" name="y_position" class="form-control">
                         </div>
                         <div class="col-sm-6 offset-sm-4">
                              <button type="submit" class="btn btn-dark">Upload</button>
                         </div>
                    </div>
               </form>
          </div>
     </div>
</body>
</html>



 

