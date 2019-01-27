<?php 

require_once('database.php');

$db = Database::getInstance();
$mysqli = $db->getConnection(); 

$sql_get_countries = "SELECT id, name FROM country";
$countries_list = $mysqli->query($sql_get_countries);

?>

<head>
<link rel="stylesheet" href="style.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>
<body>

<h3>Please enter the zip code</h3>

<div>
  <form action="">
    <label for="fname">Zip Code</label>
    <input type="text" id="zipcode" name="zipcode" placeholder="Zip Code">

    <label for="country">Country</label>
    <select id="country" name="country">
      <option value="">Select</option>
      <?php 
       while($row = mysqli_fetch_array($countries_list)){
         echo '<option value="'.$row['name'].'">'.$row['name'].'</option>';
       }
      ?>
    </select>
    </form>
</div>

<div id="show">
  <!-- Places TO BE DISPLAYED HERE -->
</div>

<script type="text/javascript">
  $(document).ready(function(){ 
    $("#country").change(function(){
      var country = $(this).val(); 
      var post_code = $('#zipcode').val();
   
      $.ajax({ /*  AJAX CALL */
        type: "POST", 
        url: "dataProvider.php",
        data: {country_data:country,code_data:post_code}, 
        success: function(result){
          $("#show").html(result);
        }
      });

    });
  });

</script>
</body>