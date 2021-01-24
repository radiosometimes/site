<!DOCTYPE html>
<html>
  <head>
    <style>
      body {
	  text-align: center;
	  background: black;
          color: white;
      }
      figure {
	margin: auto;
	width: 50%;
      }
      figure img {
	width: 100%;
	height: 100%;
      }
      table {
	margin-top: 100px;
        table-layout: fixed;
      }
      table.center {
	  margin-left:auto; 
	  margin-right:auto;
      }
      td {
	  width: 100px;
      }
      td.day {
	  background-color: #fffd38;	
      }
      td.evening {
	  background-color: #fd9927;
      }
      td.late {
	  color: #fff;
	  background-color: #9826fb;
      }
    </style>
  </head>
  <body>
    <img id="banner" src="imgs/banner.png" alt="Radio Sometimes"/>
    <?php 
      $file = '/var/www/radiosometimes/data/data.json';
      $data = json_decode(file_get_contents($file), true);
      if ($data['radio'] === 'off') echo '<h1 id="off">There\'s no radio. Come back some other time.</h1>';
      else if ($data['radio'] === 'on') echo '<h1>There\'s radio. Listen!</h1><audio controls><source src="http://104.141.73.128:8000/occasional" type="audio/mpeg"></audio></div>';
      if ($data['video'] === 'on') echo '<div><h1>There\'s TV. <a target="_external" href="'.$data['video_link'].'">Watch!</a></h1></div>';
      if ($data['img_src'] != '') echo '<figure><img src="'.$data['img_src'].'" alt="Smiley face"><figcaption>'.$data['caption'].'</figcaption></figure>';
    ?>

    
    <table  class="center">
      <tr>
	<th>Email</th>
	<th>TXT</th>
	<th>Voicemail</th>
	<th>Twitter</th>
	<th>Insta</th>
      </tr>
      <tr>
	<td>
	  <a href="mailto:radiosometimeslive@gmail.com">radiosometimeslive@gmail.com</a>
	</td>
	<td>
	  +1-718-635-0736
	</td>
	<td>
	  +1-718-635-0736
	</td>
	<td>
	  <a href="https://twitter.com/radiosometimes">@radiosometimes</a>
	</td>
	<td>
	  <a href="https://instagram.com/radiosometimes">@radiosometimes</a>
	</td>
      </tr>
    </table>    
  </body>
</html>
