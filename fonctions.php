<?php
// modifier le comportement en cas d'erreur à l'ouverture de la bdd
// ecrire la fonction securise à la fin
// les tableaux groupe,niveau,creneau ne sont pas sécurisés dans valide_formulaire au début : faisable?
// recupere_données_creneau //creneau_joueur : à sécuriser la récup de chaque ligne de la requete et le tableau unserialise

function coucou() {
     $to="test@gmail.com";
     $subject="Bonjour";
     $message="Petit message en provenance du site";
     $headers = 'From: toulesjours@ladoumegue.fr'; 
     mail($to,$subject,$message,$headers); 
}

function tableau_affichage($données) {
    echo  '[["joueur 1","joueur 2","joueur 3"],["joueur 1","joueur 2"],["competition"],["joueur 1","joueur 2","joueur 3","joueur 4"]]';    
}

function valide_journee($jour,$heure) {
    global $mysqli;
    $res=mysqli_query($mysqli, 'SELECT * FROM ATTENTE WHERE jour="'.$jour.'" AND heure="'.$heure.'";');
    $cmdbase='INSERT INTO VALIDE VALUES ("';
    while ($row=mysqli_fetch_assoc($res)) {
        $cmd=$cmdbase.$row['nom'].'","'.$row['prénom'].'","'.$jour.'","'.$heure.'","'.$row['terrain'].'");';
        //echo $cmd."\n";
        mysqli_query($mysqli, $cmd);
    }
    $cmd='DELETE FROM ATTENTE WHERE jour="'.$jour.'" AND heure="'.$heure.'";';
    //echo $cmd;
    mysqli_query($mysqli, $cmd);
}

function supprime_joueur($nom,$prenom,$jour,$heure,$terrain,$fichier) {
    global $mysqli;
    $cmd='DELETE FROM '.$fichier.' WHERE nom="'.$nom.'" AND prénom="'.$prenom.'" AND jour="'.$jour.'" AND heure="'.$heure.'" AND terrain="'.$terrain.'";';
    //echo $cmd;
    mysqli_query($mysqli, $cmd);
}

function valide_joueur($nom,$prenom,$jour,$heure,$terrain) {
    global $mysqli;
    $cmd='DELETE FROM ATTENTE WHERE nom="'.$nom.'" AND prénom="'.$prenom.'" AND jour="'.$jour.'" AND heure="'.$heure.'" AND terrain="'.$terrain.'";';
    //echo $cmd;
    mysqli_query($mysqli,$cmd);
    $cmd='INSERT INTO VALIDE VALUES ("'.$nom.'","'.$prenom.'","'.$jour.'","'.$heure.'","'.$terrain.'");';
    //echo $cmd;
    mysqli_query($mysqli,$cmd);
}

function affiche_joueurs($jour,$heure,$terrain,$fichier,$couleur) {
    global $mysqli; 
    $res=mysqli_query($mysqli, 'SELECT * FROM '.$fichier.' WHERE jour="'.$jour.'" AND heure="'.$heure.'" AND terrain="'.$terrain.'";'); 
    while ($row=mysqli_fetch_assoc($res)) {
        echo '<TR><TD style="color : '.$couleur.';" ';
        echo 'onMouseOver=\'pardessus("'.$row['prénom'].'")\'';
        echo '>'.$row['prénom'].' '.$row['nom'];
        echo ' <button type="button" onclick="supprime_joueur(\''.$row['prénom'].'\',\''.$row['nom'].'\',\''.$row['jour'].'\',\''.$row['heure'].'\',\''.$row['terrain'].'\',\''.$fichier.'\')">xx</button>';
        if ($fichier=='ATTENTE') {echo ' <button type="button" onclick="valide_joueur(\''.$row['prénom'].'\',\''.$row['nom'].'\',\''.$row['jour'].'\',\''.$row['heure'].'\',\''.$row['terrain'].'\')">ok</button>';}
        echo '</TD></TR>';
        
    }
}

function supprime_creneau() {
    global $mysqli;
    $les_creneaux=[];
    for ($i=0;$i<count($_POST['creneau']);$i++) {
        $données=recupere_donnees_creneau($_POST['creneau'][$i]);
        $cmd='DELETE FROM CRENEAUX WHERE jour="'.$données['jour'].'" AND heure="'.$données['heure'].'";';
        //echo $cmd;
        mysqli_query($mysqli,$cmd);
    }
}

function reinitialise_creneau() { 
    global $mysqli; 
    $requete=mysqli_query($mysqli, 'SELECT * FROM CRENEAUX;');
    $les_creneaux=[];
    for ($i=0;$i<count($_POST['creneau']);$i++) {
        $données=recupere_donnees_creneau($_POST['creneau'][$i]);
        array_push($les_creneaux,$données);
        $cmddeb='DELETE FROM ';
        $cmdfin=' WHERE jour="'.$données['jour'].'" AND heure="'.$données['heure'].'";';
        mysqli_query($mysqli,$cmddeb.'ATTENTE'.$cmdfin); 
        mysqli_query($mysqli,$cmddeb.'VALIDE'.$cmdfin); 
    }
    $res=mysqli_query($mysqli, 'SELECT * FROM TABLESSA;');
    while ($row=mysqli_fetch_assoc($res)) { 
        $cmd='INSERT INTO ATTENTE VALUES ("'.$row['nom'].'","'.$row['prénom'].'","'.$row['téléphone'].'","'.$row['mail'].'","'.$row['groupe'].'","'.$row['niveau'].'","'.$row['partenaire'].'","'.$row['jour'].'","'.$row['heure'].'","';
        for ($i=0;$i<count($les_creneaux);$i++) { 
            if ($les_creneaux[$i]['jour']==$row['jour'] && $les_creneaux[$i]['heure']==$row['heure'])  {
                if ($les_creneaux[$i]['T1']=="masculin" && (($row['groupe'] & 1)==1)) { mysqli_query($mysqli,$cmd.'T1");'); }
                if ($les_creneaux[$i]['T1']=="féminin" && (($row['groupe'] & 2)==2)) { mysqli_query($mysqli,$cmd.'T1");'); }
                if ($les_creneaux[$i]['T1']=="mixte" && (($row['groupe'] & 4)==4)) { mysqli_query($mysqli,$cmd.'T1");'); }
                if ($les_creneaux[$i]['T2']=="masculin" && (($row['groupe'] & 1)==1)) { mysqli_query($mysqli,$cmd.'T2");'); }
                if ($les_creneaux[$i]['T2']=="féminin" && (($row['groupe'] & 2)==2)) { mysqli_query($mysqli,$cmd.'T2");'); }
                if ($les_creneaux[$i]['T2']=="mixte" && (($row['groupe'] & 4)==4)) { mysqli_query($mysqli,$cmd.'T2");'); }
                if ($les_creneaux[$i]['T3']=="masculin" && (($row['groupe'] & 1)==1)) { mysqli_query($mysqli,$cmd.'T3");'); }
                if ($les_creneaux[$i]['T3']=="féminin" && (($row['groupe'] & 2)==2)) { mysqli_query($mysqli,$cmd.'T3");'); }
                if ($les_creneaux[$i]['T3']=="mixte" && (($row['groupe'] & 4)==4)) { mysqli_query($mysqli,$cmd.'T3");'); }
                if ($les_creneaux[$i]['T4']=="masculin" && (($row['groupe'] & 1)==1)) { mysqli_query($mysqli,$cmd.'T4");'); }
                if ($les_creneaux[$i]['T4']=="féminin" && (($row['groupe'] & 2)==2)) { mysqli_query($mysqli,$cmd.'T4");'); }
                if ($les_creneaux[$i]['T4']=="mixte" && (($row['groupe'] & 4)==4)) { mysqli_query($mysqli,$cmd.'T4");'); }
            } 
        }
    }       
}

function recupere_donnees_creneau_joueurs($serialised_tab,$table) { 
    global $mysqli;
    $val=unserialize($serialised_tab);
    $jour=securise($val[0]);
    $heure=securise($val[1]); 
    $requete=mysqli_query($mysqli, 'SELECT * FROM '.$table.' WHERE jour="'.$jour.'" AND heure="'.$heure.'";');
    $resultat=[]; 
    while ($row=mysqli_fetch_assoc($requete)) {
        array_push($resultat,$row);
    }
    return $resultat;
}

function recupere_donnees_creneau($serialised_tab) { 
    global $mysqli;
    $val=unserialize($serialised_tab);
    $jour=securise($val[0]);
    $heure=securise($val[1]); 
    $requete=mysqli_query($mysqli, 'SELECT * FROM CRENEAUX WHERE jour="'.$jour.'" AND heure="'.$heure.'";');
    while ($row=mysqli_fetch_assoc($requete)) {
        return $row;
    }
    echo "créneau inexistant";
    die();
}

function lecture_creneaux() {
    global $mysqli; 
    $requete=mysqli_query($mysqli, 'SELECT * FROM CRENEAUX');
    $res=[];
    while ($row=mysqli_fetch_assoc($requete)) {
        $jour=securise($row['jour']);
        $heure=securise($row['heure']);
        array_push($res,[$jour,$heure]);
    }
    return $res;
}

function creation_creneau() {
    global $mysqli;
    $jour=securise($_POST['jour']);
    $heure=securise($_POST['heure']);
    $t1=securise($_POST['T1']);
    $t2=securise($_POST['T2']);
    $t3=securise($_POST['T3']);
    $t4=securise($_POST['T4']);
    $c1=securise($_POST['C1']);
    $c2=securise($_POST['C2']);
    $c3=securise($_POST['C3']);
    $c4=securise($_POST['C4']);
    $cmd='INSERT INTO CRENEAUX VALUES ("'.$jour.'","'.$heure.'","'.$t1.'","'.$t2.'","'.$t3.'","'.$t4.'","'.$c1.'","'.$c2.'","'.$c3.'","'.$c4.'")';
    //echo $cmd;
    mysqli_query($mysqli, $cmd);
}

function valide_demande_creneaux() {
    global $mysqli;
    $nom=securise($_POST['nom']);
    $prenom=securise($_POST['prénom']);
    $telephone=securise($_POST['téléphone']);
    $mail=securise($_POST['mail']);
    $partenaire=securise($_POST['partenaire']);
    $groupe=flag_groupe();
    $niveau=flag_niveau();
    $creneau=$_POST['creneau'];
    $cmd1='INSERT INTO ';
    $cmd2=' VALUES ("'.$nom.'","'.$prenom.'","'.$telephone.'","'.$mail.'","'.$groupe.'","'.$niveau.'","'.$partenaire.'","';
    $requete=mysqli_query($mysqli, 'SELECT * FROM CRENEAUX;');
    $les_creneaux=[];
    while ($row=mysqli_fetch_assoc($requete)) {
        array_push($les_creneaux,$row);
    }    
    for ($j=0;$j<count($creneau);$j++) { 
        $val=unserialize($creneau[$j]);
        $cmdfinal1=$cmd1."TABLESSA".$cmd2.$val[0].'","'.$val[1].'");';
        mysqli_query($mysqli, $cmdfinal1);
        $cmdfinal2=$cmd1."ATTENTE".$cmd2.$val[0].'","'.$val[1].'","';
        for ($i=0;$i<count($les_creneaux);$i++) { 
            if ($les_creneaux[$i]['jour']==$val[0] && $les_creneaux[$i]['heure']==$val[1])  {
                if ($les_creneaux[$i]['T1']=="masculin" && (($groupe & 1)==1)) { mysqli_query($mysqli,$cmdfinal2.'T1");'); }
                if ($les_creneaux[$i]['T1']=="féminin" && (($groupe & 2)==2)) { mysqli_query($mysqli,$cmdfinal2.'T1");'); }
                if ($les_creneaux[$i]['T1']=="mixte" && (($groupe & 4)==4)) { mysqli_query($mysqli,$cmdfinal2.'T1");'); }
                if ($les_creneaux[$i]['T2']=="masculin" && (($groupe & 1)==1)) { mysqli_query($mysqli,$cmdfinal2.'T2");'); }
                if ($les_creneaux[$i]['T2']=="féminin" && (($groupe & 2)==2)) { mysqli_query($mysqli,$cmdfinal2.'T2");'); }
                if ($les_creneaux[$i]['T2']=="mixte" && (($groupe & 4)==4)) { mysqli_query($mysqli,$cmdfinal2.'T2");'); }
                if ($les_creneaux[$i]['T3']=="masculin" && (($groupe & 1)==1)) { mysqli_query($mysqli,$cmdfinal2.'T3");'); }
                if ($les_creneaux[$i]['T3']=="féminin" && (($groupe & 2)==2)) { mysqli_query($mysqli,$cmdfinal2.'T3");'); }
                if ($les_creneaux[$i]['T3']=="mixte" && (($groupe & 4)==4)) { mysqli_query($mysqli,$cmdfinal2.'T3");'); }
                if ($les_creneaux[$i]['T4']=="masculin" && (($groupe & 1)==1)) { mysqli_query($mysqli,$cmdfinal2.'T4");'); }
                if ($les_creneaux[$i]['T4']=="féminin" && (($groupe & 2)==2)) { mysqli_query($mysqli,$cmdfinal2.'T4");'); }
                if ($les_creneaux[$i]['T4']=="mixte" && (($groupe & 4)==4)) { mysqli_query($mysqli,$cmdfinal2.'T4");'); }
            } 
        }
    }
}

function ouvre_bdd() {
    global $mysqli;
    $mysqli = mysqli_connect("localhost");  // identifiant persos
    if (mysqli_connect_errno()) {
        echo "Erreur lors de la connection à la base de données : ".mysqli_connect_error();
        die();
    } else{ 
        $mysqli->set_charset("utf8");
    }
}

function ferme_bdd() {
    global $mysqli;
    $mysqli->close();
}

function flag_groupe() { //transforme le tableau de groupes en une suite de bits  
    // !!!!!!!!!!!!!!!!!!! non sécurisé.
    $tab=$_POST['groupe'];
    $res=0;
    for ($i=0;$i<count($tab);$i++) {
        if ($tab[$i]=="masculin" && (($res & 1)==0)) {
            $res+=1;
        }
        if ($tab[$i]=="féminin" && (($res & 2)==0)) {
            $res+=2;
        }
        if ($tab[$i]=="mixte" && (($res & 4)==0)) {
            $res+=4;
        }
    }
    return $res;
}

function flag_niveau() {
    return 1;
}

function securise($txt) {
    return $txt;
}


?>
