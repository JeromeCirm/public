<!DOCTYPE html>

<HTML>
    <META http-equiv=content-type content="text/html; charset=utf-8">
    
    <HEAD>
        <TITLE>Gestion des créneaux</TITLE>
        <LINK rel="stylesheet" href="style.css?=<?php echo time(); //pour forcer le css a ne pas être en cache et jouer des tours en dev ?>">
    </HEAD>
    
<STYLE>

</STYLE>

<?php
require "fonctions.php";
ouvre_bdd();
if (isset($_POST['jour'])) {
    creation_creneau();
}
$creneaux=lecture_creneaux();
ferme_bdd();
?>

<BODY>

<?php 

if (isset($_POST['reinitialise']) && ($_POST['reinitialise']=="true")) { 
    ouvre_bdd();
    reinitialise_creneau();
    ferme_bdd();
}
if (isset($_POST['supprimecreneau']) && ($_POST['supprimecreneau']=="true")) { 
    ouvre_bdd();
    supprime_creneau();
    $creneaux=lecture_creneaux();
    ferme_bdd();
} else if (isset($_POST['creneau'])) {  echo '<BR>';
    ouvre_bdd();
    if (isset($_POST['supprime']) and ($_POST['supprime']=="true")) { 
        supprime_joueur($_POST['hidnom'],$_POST['hidprenom'],$_POST['hidjour'],$_POST['hidheure'],$_POST['hidterrain'],$_POST['hidfichier']);
    } else if (isset($_POST['valide']) and ($_POST['valide']=="true")) { 
        valide_joueur($_POST['hidnom'],$_POST['hidprenom'],$_POST['hidjour'],$_POST['hidheure'],$_POST['hidterrain']);
    } else if (isset($_POST['validejournee']) and ($_POST['validejournee']=="true")) { 
        valide_journee($_POST['hidjour'],$_POST['hidheure']);
    }
    //echo '<BR>en bleu les personnes en attente, en noir celles déjà confirmées.   Click sur xx pour supprimer et sur ok pour valider <BR><BR>';
    echo '<BR><DIV id="docjoueur" style="height 30px;">Coucou</DIV><BR>';
    for ($i=0;$i<count($_POST['creneau']);$i++) {
        $données=recupere_donnees_creneau($_POST['creneau'][$i]);
        echo $données['jour'].' '.$données['heure'];
    ?>
        <DIV class="unjour">
            <TABLE>
                <TR><TH>T1 : <?php echo $données['T1']; ?></TH></TR>
                <?php
                affiche_joueurs($données['jour'],$données['heure'],"T1","VALIDE","black");
                affiche_joueurs($données['jour'],$données['heure'],"T1","ATTENTE","blue");
                ?>
            </TABLE>

            <TABLE>
                <TR><TH>T2 : <?php echo $données['T2']; ?></TH></TR>
                <?php
                affiche_joueurs($données['jour'],$données['heure'],"T2","VALIDE","black");
                affiche_joueurs($données['jour'],$données['heure'],"T2","ATTENTE","blue");
                ?>
            </TABLE>

            <TABLE>
                <TR><TH>T3 : <?php echo $données['T3']; ?></TH></TR>
                <?php
                affiche_joueurs($données['jour'],$données['heure'],"T3","VALIDE","black");
                affiche_joueurs($données['jour'],$données['heure'],"T3","ATTENTE","blue");
                ?>
            </TABLE>
                
            <TABLE>
                <TR><TH>T4 : <?php echo $données['T4']; ?></TH></TR>
                <?php
                affiche_joueurs($données['jour'],$données['heure'],"T4","VALIDE","black");
                affiche_joueurs($données['jour'],$données['heure'],"T4","ATTENTE","blue");
               ?>
            </TABLE>
            
        </DIV>
        <button type='button' onclick='valide_journee(<?php echo '"'.$données['jour'].'","'.$données['heure']; ?>")'>Valider toutes les personnes en attente <?php echo $données['jour'].' '.$données['heure']; ?></button>
        <button type='button' onclick='creation_tableau_journee(<?php tableau_affichage($données); ?>)'>Créer le tableau pour <?php echo $données['jour'].' '.$données['heure']; ?></button>
        <BR><BR>   
<?php
    }
    ferme_bdd();
} 

?>
    <a href="index.php">Page de saisie</a><BR><BR>
        <form id="CreationCreneau" method="post" action="gestion.php">
            Jour : <input id="jour" name="jour">
            Heure : <input id="heure" name="heure"><BR>
            Terrain 1 : <input name="T1">
            Terrain 2 : <input name="T2">
            Terrain 3 : <input name="T3">
            Terrain 4 : <input name="T4"><BR>
            Couleur 1 : <input name="C1">
            Couleur 2 : <input name="C2">
            Couleur 3 : <input name="C3">
            Couleur 4 : <input name="C4">
        </form>
     <button type="button" onclick="creation_creneau()">Créer un nouveau créneau avec les informations précédentes</button>
       <form id="GestionCreneau" method="post" action="gestion.php"> 
            <ul>
<?php
    for ($i=0;$i<count($creneaux);$i++) {
        $seri=serialize($creneaux[$i]);
        echo "<li><input type='checkbox' name='creneau[]' ";
        if (in_array($seri,$_POST['creneau'])) { echo 'checked ';}
        echo "value='".$seri."'>".$creneaux[$i][0]." ".$creneaux[$i][1]."</li>";
    }
?>            </ul>
        <input hidden id='reinitialise' name='reinitialise' value="false">
        <input hidden id='supprime' name='supprime' value="false">
        <input hidden id='supprimecreneau' name='supprimecreneau' value="false">
        <input hidden id='valide' name='valide' value="false">
        <input hidden id='validejournee' name='validejournee' value="false">
        <input hidden id='hidnom' name='hidnom'>
        <input hidden id='hidprenom' name='hidprenom'>
        <input hidden id='hidjour' name='hidjour'>
        <input hidden id='hidheure' name='hidheure'>
        <input hidden id='hidterrain' name='hidterrain'>
        <input hidden id='hidfichier' name='hidfichier'>
        </form>
    <button type="button" onclick="gestion_creneau()">Afficher les créneaux choisis pour les gérer</button>
    <button type="button" onclick="reinitialise_creneau()">Réinitialiser les créneaux choisis</button>
    <button type="button" onclick="supprime_creneau()">Supprimer les créneaux choisis</button><BR>
    <a href="coucou.php">Faire coucou à Alex! (ne pas utiliser trop souvent)</a><BR><BR>


</BODY>
<SCRIPT src="java.js?=<?php echo time(); //pour forcer le js a ne pas être en cache et jouer des tours en dev ?>">  </SCRIPT>
<script src='build/pdfmake.min.js'></script>
<script src='build/vfs_fonts.js'></script>
</HTML>








