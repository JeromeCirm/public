var Formulaire=document.getElementById("Formulaire");
var GestionCreneau=document.getElementById("GestionCreneau");
var CreationCreneau=document.getElementById("CreationCreneau");
var jour=document.getElementById("jour");
var heure=document.getElementById("heure");
var reinitialise=document.getElementById("reinitialise");
var DemandeCreneau=document.getElementsByName("creneau[]");
var supprime=document.getElementById('supprime');
var supprimecreneau=document.getElementById('supprimecreneau');
var valide=document.getElementById('valide');
var validejournee=document.getElementById('validejournee');
var hidnom=document.getElementById('hidnom');
var hidprenom=document.getElementById('hidprenom');
var hidjour=document.getElementById('hidjour');
var hidheure=document.getElementById('hidheure');
var hidterrain=document.getElementById('hidterrain');
var hidfichier=document.getElementById('hidfichier');
var docjoueur=document.getElementById('docjoueur');

function pardessus(txt) {
    docjoueur.textContent='texte : '+txt
}

function creation_tableau_journee(tab) { 
    var PJ1=[]
    for (i=0;i<tab[0].length;i++) {
        PJ1.push([tab[0][i]])
    }
    var J1={table : {body : PJ1},layout: 'noBorders'}
    var PJ2=[]
    for (i=0;i<tab[1].length;i++) {
        PJ2.push([{text : tab[1][i],fillColor : 'red'}])
    }
    var J2={table : {body : PJ2},layout: 'noBorders'}
    var PJ3=[]
    for (i=0;i<tab[2].length;i++) {
        PJ3.push([tab[2][i]])
    }
    var J3={table : {body : PJ3},layout: 'noBorders'}
    var PJ4=[]
    for (i=0;i<tab[3].length;i++) {
        PJ4.push([tab[3][i]])
    }
    var J4={table : {body : PJ4},layout: 'noBorders',fillColor: 'yellow'}
    var docDefinition = {
	    content: [
		    {
			table: {
				body: [
					['T1','T2',{text : 'T3',fillColor: 'yellow'},'T4'],
					[J1,J2,J3,J4]
				]
			}
		}
	    ]
    };
    pdfMake.createPdf(docDefinition).open();
}

function valide_journee(jour,heure) {
    validejournee.value="true"
    hidjour.value=jour
    hidheure.value=heure
    GestionCreneau.submit()
}

function valide_formulaire() { 
    vide=true
    for (i=0;i<DemandeCreneau.length;i++) {
        if (DemandeCreneau[i].checked) { vide=false}
    }
    if (vide) { alert('Il faut au moins demander un créneau') }
    else {Formulaire.submit() }
}

function gestion_creneau() { 
    vide=true
    for (i=0;i<DemandeCreneau.length;i++) {
        if (DemandeCreneau[i].checked) { vide=false}
    }
    if (vide) { alert('Il faut au moins demander un créneau') }
    else {GestionCreneau.submit() }
}

function supprime_creneau() {
    supprimecreneau.value="true"
    GestionCreneau.submit()
}

function reinitialise_creneau() {
    reinitialise.value="true"
    GestionCreneau.submit()
}

function creation_creneau() {
    if (jour.value=="" || heure.value=="") {
        alert("il faut au moins renseigner le jour et l'heure");
    } else {
        CreationCreneau.submit();
    }
}

function supprime_joueur(prenom,nom,jour,heure,terrain,fichier) {
    supprime.value="true"
    hidnom.value=nom
    hidprenom.value=prenom
    hidjour.value=jour
    hidheure.value=heure
    hidterrain.value=terrain
    hidfichier.value=fichier
    GestionCreneau.submit()
}

function valide_joueur(prenom,nom,jour,heure,terrain) {
    valide.value="true"
    hidnom.value=nom
    hidprenom.value=prenom
    hidjour.value=jour
    hidheure.value=heure
    hidterrain.value=terrain
    GestionCreneau.submit()
}