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
if (isset($_POST['nom'])) {
    valide_demande_creneaux();
}
$creneaux=lecture_creneaux();
ferme_bdd()
?>

<BODY>
    <a href="gestion.php">Page de gestion</a>
    <form id="Formulaire" method="post" action="index.php"> 
    <DIV class="structure_index">
        <DIV>
            <ul>
            <li>Nom : <input name="nom"></li>
            <li>Prénom : <input name="prénom"></li>
            <li>Numéro de téléphone : <input name="téléphone"></li>
            <li>Mail : <input name="mail"></li>
            <li>Groupe : <input type="checkbox" name="groupe[]" value="masculin">Masculin<input type="checkbox" name="groupe[]" value="féminin">Féminin<input type="checkbox" name="groupe[]" value="mixte">Mixte</li>
            <li>Niveau : <input type="checkbox" name="niveau[]" value="expert masculin">Expert Masculin <input type="checkbox" name="niveau[]" value="expert féminin">Expert Féminin
                          <input type="checkbox" name="niveau[]" value="expert mixte">Expert Mixte</li>
            <li>Partenaires : <input name="partenaire"></li></ul>
        </DIV>
            <ul>
<?php
    for ($i=0;$i<count($creneaux);$i++) {
        echo "<li><input type='checkbox' name='creneau[]' value='".serialize($creneaux[$i])."'>".$creneaux[$i][0]." ".$creneaux[$i][1]."</li>";
    }
?>
            </ul>
        <DIV>
            
        </DIV>
        
    </DIV>
    </form>
    <button type="button" onclick="valide_formulaire()">Soumettre ces demandes de créneaux</button>
</BODY>
<SCRIPT src="java.js?=<?php echo time(); //pour forcer le js a ne pas être en cache et jouer des tours en dev ?>">  </SCRIPT>
</HTML>


