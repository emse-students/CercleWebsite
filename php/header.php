<header>
	
	<div class="groslogo">
		<a href="index.php"><img src="images/groslogo_cercle.png"></a>
	</div>	
	<div class="barreheader">
		<div class="vide"></div>
		<?php 
		if (isset($_SESSION['droit']))
		{ ?>
			<a href="compte.php">
				<div class="lienheader <?php if($page==1){echo"selected";}?>">
					Mon compte
				</div>
			</a>
			<a href="stats.php">
				<div class="lienheader <?php if($page==2){echo"selected";}?>">
					Stats
				</div>
			</a>
		<?php }  
		if (isset($_SESSION['droit']) AND ($_SESSION['droit']=="cercle" OR $_SESSION['droit']=="cercleux") AND (!isset($_SESSION["perm"]) OR $_SESSION["perm"]==NULL))
		{ ?>
			<a href="open_perm.php">
				<div class="lienheader <?php if($page==3){echo"selected";}?>">
					Ouvrir une perm
				</div>
			</a>
		<?php }  
		if (isset($_SESSION['droit']) AND ($_SESSION['droit']=="cercle" OR $_SESSION['droit']=="cercleux") AND isset($_SESSION["perm"]) AND $_SESSION["perm"]!=NULL)
		{ ?>
			<a href="perm.php">
				<div class="lienheader <?php if($page==4){echo"selected";}?>">
					Perm
				</div>
			</a>
		<?php }  
		if (isset($_SESSION['droit']) AND $_SESSION['droit']=="cercle")
		{ ?>
			<a href="compte.php?id=0">
				<div class="lienheader <?php if($page==5){echo"selected";}?>">
					Historique
				</div>
			</a>
			<a href="recharge.php">
				<div class="lienheader <?php if($page==6){echo"selected";}?>">
					Rechargement
				</div>
			</a>
			<a href="gestion.php">
				<div class="lienheader <?php if($page==7){echo"selected";}?>">
					Gestion
				</div>
			</a>
		<?php } 
		if (isset($_SESSION['droit']))
		{ ?>
			<a href="php/logout.php">
				<div class="lienheader <?php if($page==8){echo"selected";}?>" style="padding: 3px; ">
					<div style="width: 1.5em;"><img src="images/logout.png"></div>
				</div>
			</a>
		<?php }
		?>
		
	</div>    		
</header>