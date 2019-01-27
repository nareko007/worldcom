<?php 

require_once('database.php');

$db = Database::getInstance();
$mysqli = $db->getConnection(); 

if(!empty($_POST["country_data"])){
  if(!empty($_POST["code_data"])){

    //get the data from ajax call
    $country_name = $_POST["country_data"];
    $post_code = $_POST["code_data"];

    $sql_get_countries = "SELECT id,country_code  FROM country Where name='$country_name'";

    if (mysqli_query($mysqli, $sql_get_countries)) {

      $list = $mysqli->query($sql_get_countries);
      $row = mysqli_fetch_array($list);
      
      $country_code = $row['country_code'];

    }else {
      echo "Error: " . $sql . "<br>" . mysqli_error($mysqli);
    }

    //check if there is such a country
    if(!empty($row['id'])){ // 

      $country_id = $row['id'];
      $sql_get_places = "SELECT id, post_code ,place_name,longitude,latitude,state,country_id  FROM places Where country_id='$country_id' AND post_code = '$post_code'";
      
      if(mysqli_fetch_array($mysqli->query($sql_get_places))){ // if the place is exist in the database 
        
        $places_list = $mysqli->query($sql_get_places);
        echo '<table style="width:100%">
         <tr>
          <th>Place Name</th>
          <th>State</th> 
          <th>Longitude</th>
          <th>Latitude</th>
        </tr>';
        while($places = mysqli_fetch_array($places_list)){
          echo '<tr>
          <td>'.$places["place_name"].'</td>
          <td>'.$places["state"].'</td>
          <td>'.$places["longitude"].'</td>
          <td>'.$places["latitude"].'</td>
        </tr>';
        }
       echo '</table>';
      }
      else{ //get that place via API call and add into the database 

        $url = 'api.zippopotam.us/'.$country_code.'/'.$post_code.'';
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Accept: application/json'));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($curl);
        $datasearch = json_decode($data, true);
    
        if(!empty($datasearch)) { 

        $country = $datasearch['country'];
        $post_code = $datasearch['post code'];
        $places = $datasearch['places'];

        echo '<table style="width:100%">
        <tr>
         <th>Place Name</th>
         <th>State</th> 
         <th>Longitude</th>
         <th>Latitude</th>
        </tr>';

        foreach($places as $place){
            $place_name = $place['place name'];
            $longitude = $place['longitude'];
            $state = $place['state'];
            $latitude = $place['latitude'];
            $place_name = $place['place name'];

            $sql = "INSERT INTO places (country_id, place_name, longitude,latitude,post_code,state)
            VALUES ('$country_id', '$place_name', '$longitude','$latitude','$post_code','$state')";

            if (mysqli_query($mysqli, $sql)) { // insert data into the places table
             
           
            echo '<tr>
               <td>'.$place_name.'</td>
               <td>'.$state.'</td>
               <td>'.$longitude.'</td>
               <td>'.$latitude.'</td>
             </tr>';
    
            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($mysqli);
            }
        }

        echo "</table>";
  
        }
        else { //it user provided wrong data
          echo "There are no such data";
        }
        curl_close($curl);
        
      }
    }
    exit;
  }else { // if user didn't add the zip code
    echo "Please enter the zip code";
  }
}

?>