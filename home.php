<?php
session_start();
include "db_conn.php";

if (!isset($_SESSION['login_name'])){


if (isset($_POST['login_name']) && isset($_POST['password'])){
	function validate($data){
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}
		
	$login_name = validate($_POST['login_name']);
	$password = validate($_POST['password']);	
	
	#login.php zu index.php!!!
	if (empty($login_name)) {
		header("Location: login.php?error=Bitte Benutzername eingeben!");
		exit();
	}else if (empty($password)) {
		header("Location: login.php?error=Bitte Passwort eingeben!");
		exit();
	}else{
		$sql="SELECT * FROM dspia_user WHERE login_name='$login_name' AND password='$password'";
		
		$result = mysqli_query($conn, $sql);
		
		if (mysqli_num_rows($result) === 1){
			$row = mysqli_fetch_assoc($result);
			if ($row['login_name'] === $login_name && $row['password'] === $password){
				$_SESSION['login_name'] = $row['login_name'];
				$_SESSION['is_admin'] = $row['is_admin'];
				$_SESSION['full_name'] = $row['full_name'];
				$_SESSION['user_id'] = $row['user_id'];
			}else{
				#login.php zu index.php!!!
				header("Location: login.php?error=Benutzername oder Passwort falsch!");
				exit();
			}
		}else{
			#login.php zu index.php!!!
			header("Location: login.php?error=Benutzername oder Passwort falsch!");
			exit();
		}
	}

}else{
	header("Location: login.php");
	exit();
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IP-Adressverwaltung</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="shortcut icon" type="image/x-icon" href="img/home/test.png">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script>
	$(document).ready(function(){
	  $("#suchleiste").on("keyup", function() {
		var value = $(this).val().toLowerCase();
		$("#myTable tr").filter(function() {
		  $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
		});
	  });
	});
	</script>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
	<script type="text/javascript">
    $(document).ready(function () {
        $("#ddlStandort,#ddlStatus,#ddlInfrastruktur").on("change", function () {
            var standort = $('#ddlStandort').find("option:selected").val();
            var status = $('#ddlStatus').find("option:selected").val();
			var infrastructure = $('#ddlInfrastruktur').find("option:selected").val();
            SearchData(standort, status, infrastructure)
        });
    });
    function SearchData(standort, status, infrastructure) {
        if (standort.toUpperCase() == 'ALL' && status.toUpperCase() == 'ALL' && infrastructure.toUpperCase() == 'ALL') {
            $('#table11 tbody tr').show();
        } else {
            $('#table11 tbody tr:has(td)').each(function () {
                var rowStandort = $.trim($(this).find('td:nth-child(11)').text());
                var rowStatus = $.trim($(this).find('td:nth-child(7)').text());
                var rowInfrastructure = $.trim($(this).find('td:nth-child(12)').text());
                if (standort.toUpperCase() != 'ALL' && status.toUpperCase() != 'ALL' && infrastructure.toUpperCase() != 'ALL') {
                    if (rowStandort.toUpperCase() == standort.toUpperCase() && rowStatus.toUpperCase() == status.toUpperCase()) {
                        $(this).show();
                    } else if (rowStatus.toUpperCase() == status.toUpperCase() && rowInfrastructure == infrastructure) {
                        $(this).show();
                    } else if (rowStandort.toUpperCase() == standort.toUpperCase() && rowStatus.toUpperCase() == status.toUpperCase() && rowInfrastructure == infrastructure) {
                        $(this).show();
					} else {
						$(this).hide();
					}
                } else if ($(this).find('td:nth-child(11)').text() != '' || $(this).find('td:nth-child(12)').text() != '' || $(this).find('td:nth-child(7)').text() != '') {
                    if (standort != 'all') {
                        if (rowStandort.toUpperCase() == standort.toUpperCase()) {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    }
                    if (status != 'all') {
                        if (rowStatus.toUpperCase() == status.toUpperCase()) {
                            $(this).show();
                        }
                        else {
                            $(this).hide();
                        }
                    }
					if (infrastructure != 'all') {
                        if (rowInfrastructure == infrastructure) {
                            $(this).show();
                        }
                        else {
                            $(this).hide();
                        }
                    }
                }
 
            });
        }
    }
</script>
</head>

<body>
        <div class="row header">
                <a href="home.php"><img id="drv" src="img/neueip/logo_drv_berlin-brandenburg_web.jpg"></a>
				<div class= "header-text">
					<h2>IP-Adressverwaltung</h2>
				</div>
				<div class= "header-line">
					<h1>|</h1>
				</div>
	        <div class="col-50 rechts">
			<?php
			if($_SESSION['is_admin'] =='1'){
				?>
				<div class="verwaltung">
					<button class="dropbtn"><b>Verwaltung</b></button>
				<div class="dropdown-content">
					<a href="benutzer.php">Benutzer</a>
					<a href="status.php">Status</a>
					<a href="standort.php">Standort</a>
					<a href="betriebssystem.php">Betriebssystem</a>
					<a href="infrastruktur.php">Infrastruktur</a>
				</div>
				</div>
			<?php
			}
			?>
                <a href="logout.php"><img src="img/home/logout.png" class="logout"></a>
				<div class= "user">
					<h3>| Benutzer: <?php print_r($_SESSION['login_name']); ?> |</h3>
				</div>
            </div>
			<div class="gesamtPapierkorb">
			<a href="home.php"><img src="img/home/gesamtPapierkorb.png"></a>
			</div>
        </div>
		<div class="reload">
			<a href="home.php"><img src="img/home/refresh.png"></a>
		</div>
        <div class="filtern">
		<?php
		require("db_conn.php");
		$sql = "SELECT * FROM `dspia_ip_state` ORDER BY state_id";
		$all_states = mysqli_query($conn,$sql);
		?>
			<select id="ddlStatus" name="ddlStatus"><br>
				<option value="all">Status</option>
				<?php
                while ($state = mysqli_fetch_array(
                        $all_states,MYSQLI_ASSOC)):;
            ?>
                <option value="<?php echo $state["state"];
                ?>">
                    <?php echo $state["state"];
                    ?>
                </option>
            <?php
                endwhile;
            ?>
			</select>
		<?php
		require("db_conn.php");
		$sql = "SELECT * FROM `dspia_location` ORDER BY location_id";
		$all_location = mysqli_query($conn,$sql);
		?>
			<select id="ddlStandort" name="ddlStandort">
				<option value="all">Standort</option>
				<?php
                while ($location = mysqli_fetch_array(
                        $all_location,MYSQLI_ASSOC)):;
            ?>
                <option value="<?php echo $location["location"];
                ?>">
                    <?php echo $location["location"];
                    ?>
                </option>
            <?php
                endwhile;
            ?>
			</select>
		<?php
		require("db_conn.php");
		$sql = "SELECT * FROM `dspia_infrastructure` ORDER BY infrastructure_id";
		$all_infrastructure = mysqli_query($conn,$sql);
		?>
			<select id="ddlInfrastruktur" name="ddlInfrastruktur">
				<option value="all">Infrastruktur</option>
				<?php
                while ($infrastructure = mysqli_fetch_array(
                        $all_infrastructure,MYSQLI_ASSOC)):;
            ?>
                <option value="<?php echo $infrastructure["infrastructure"];
                ?>">
                    <?php echo $infrastructure["infrastructure"];
                    ?>
                </option>
            <?php
                endwhile;
            ?>
			</select>
		</div>
		
		<input type="text" id="suchleiste" onkeyup="myFunction()" placeholder="Suchen...">
		
        </div>
		<div class="IPbutton">
			<form action="newip.php"  method="post">
			<button class="IP" type="submit"><b>+  Neue IP-Adresse</b></button>
			</form>
		</div>
       
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	
	
	
	<div class="tabelle">
	<div style="overflow-x:auto;">
	<table id=table11>
		<tr>
			<th></th>
			<th onclick="sortID()">ID</th>
			<th onclick="sortTable(1)">IP-Adresse ↕</th>
			<th onclick="sortTable(2)">Hostname ↕</th>
			<th onclick="sortTable(3)">MAC-Adresse ↕</th>
			<th onclick="sortTable(4)">PW_Neu ↕</th>
			<th onclick="sortTable(5)">Status ↕</th>
			<th onclick="sortTable(6)">Beschreibung ↕</th>
			<th onclick="sortTable(7)">Betriebssystem ↕</th>
			<th onclick="sortTable(8)">Admin ↕</th>
			<th onclick="sortTable(9)">Standort ↕</th>
			<th onclick="sortTable(10)">Infrastruktur ↕</th>
			<th onclick="sortTable(11)">Workorder ↕</th>
			<th onclick="sortTable(12)">Anmerkungen ↕</th>
		</tr>
		
<?php
require('db_conn.php');

if(isset($_GET["del"])){
    if(!empty($_GET["del"])){
		$main_id = $_GET["del"];
        $stmt = $conn->prepare("DELETE FROM dspia_main WHERE main_id = '".$main_id."'");
		
		if($stmt->execute()){
			?>
			<div class="alert">
			<span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
			<strong>
			<?php
			print "Die IP-Adresse wurde gelöscht.";
			?>
			</strong> 
			</div>
			<?php
		}else{
			print $conn->error; 
		}
    }
}

$sql = "SELECT * FROM v_dspia_main";
$result = mysqli_query($conn, $sql);

while($dsatz = mysqli_fetch_assoc($result)){
    ?>
    <tbody id="myTable">
	<tr>
		<th><a href="editIP.php?id=<?php echo $dsatz["main_id"] ?>"><img src="img/home/stift.png" class="Stift" width="auto" height="30"></a><a href="home.php?del=<?php echo $dsatz["main_id"] ?>"onclick='return confirmSubmit()'><img src="img/home/mülleimer.png" class="Mülleimer" width="auto" height="33" class="Mülleimer"></th>
		<td><?php echo $dsatz["main_id"] ?></td>
		<td><?php echo $dsatz["ip_v4"] ?></td>
        <td><?php echo $dsatz["hostname"] ?></td>
		<td><?php echo $dsatz["mac_address"] ?></td>
		<td><?php if($dsatz["pw_new"] == "1") {
			echo "Ja";
		}else if($dsatz["pw_new"] == "0"){
			echo "Nein";
		} ?></td>
		<td><?php echo $dsatz["state"] ?></td>
		<td><?php echo $dsatz["description"] ?></td>
		<td><?php echo $dsatz["op_system"] ?></td>
		<td><?php echo $dsatz["operator"] ?></td>
		<td><?php echo $dsatz["location"] ?></td>
		<td><?php echo $dsatz["infrastructure"] ?></td>
		<td><?php echo $dsatz["workorder"] ?></td>
		<td><?php echo $dsatz["remarks"] ?></td>
    </tr>
    <?php
    }
    ?>
	<colgroup>
	<col width="5%">
	</colgroup>
	</tbody>
	</table>
	</div>
	</div>
	
<script language="JavaScript" type="text/JavaScript">
<!--
function confirmSubmit()
{
    var agree=confirm("Die IP-Adresse wird unwiderruflich gelöscht!");
    if (agree)
    return true ;
    else
    return false ;
}
</script>
<script>
function sortTable(n) {
  var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
  table = document.getElementById("table11");
  switching = true;
  // Set the sorting direction to ascending:
  dir = "asc";
  /* Make a loop that will continue until
  no switching has been done: */
  while (switching) {
    // Start by saying: no switching is done:
    switching = false;
    rows = table.rows;
    /* Loop through all table rows (except the
    first, which contains table headers): */
    for (i = 1; i < (rows.length - 1); i++) {
      // Start by saying there should be no switching:
      shouldSwitch = false;
      /* Get the two elements you want to compare,
      one from current row and one from the next: */
      x = rows[i].getElementsByTagName("TD")[n];
      y = rows[i + 1].getElementsByTagName("TD")[n];
      /* Check if the two rows should switch place,
      based on the direction, asc or desc: */
      if (dir == "asc") {
        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
          // If so, mark as a switch and break the loop:
          shouldSwitch = true;
          break;
        }
      } else if (dir == "desc") {
        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
          // If so, mark as a switch and break the loop:
          shouldSwitch = true;
          break;
        }
      }
    }
    if (shouldSwitch) {
      /* If a switch has been marked, make the switch
      and mark that a switch has been done: */
      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      switching = true;
      // Each time a switch is done, increase this count by 1:
      switchcount ++;
    } else {
      /* If no switching has been done AND the direction is "asc",
      set the direction to "desc" and run the while loop again. */
      if (switchcount == 0 && dir == "asc") {
        dir = "desc";
        switching = true;
      }
    }
  }
}
</script>
<script>
function sortID() {
  var table, rows, switching, i, x, y, shouldSwitch;
  table = document.getElementById("table11");
  switching = true;
  /*Make a loop that will continue until
  no switching has been done:*/
  while (switching) {
    //start by saying: no switching is done:
    switching = false;
    rows = table.rows;
    /*Loop through all table rows (except the
    first, which contains table headers):*/
    for (i = 1; i < (rows.length - 1); i++) {
      //start by saying there should be no switching:
      shouldSwitch = false;
      /*Get the two elements you want to compare,
      one from current row and one from the next:*/
      x = rows[i].getElementsByTagName("TD")[0];
      y = rows[i + 1].getElementsByTagName("TD")[0];
      //check if the two rows should switch place:
      if (Number(x.innerHTML) > Number(y.innerHTML)) {
        //if so, mark as a switch and break the loop:
        shouldSwitch = true;
        break;
      }
    }
    if (shouldSwitch) {
      /*If a switch has been marked, make the switch
      and mark that a switch has been done:*/
      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      switching = true;
    }
  }
}
</script>
</body>
</html>
