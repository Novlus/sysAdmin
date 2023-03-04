<?php
// Définition de la variable $ldap_dn avec le DN (Distinguished Name) de l'administrateur de l'annuaire LDAP.
$ldap_dn = "cn=admin,dc=groupe11,dc=fr";
$dn = "dc=groupe11,dc=fr";


// Définition de la variable $ldap_password avec le mot de passe de l'administrateur de l'annuaire LDAP.
$ldap_password = "charlemagne";

// Connexion à l'annuaire LDAP en utilisant la fonction ldap_connect().
$ldap_con = ldap_connect("ldap://docky.netlor.fr:3731"); // mettre ldap:// en premier puis bien recuperer  ce qui se trouver apés le @  lors d'une connexion ssl et ne pas oublier de mettre le .fr ou .ce que tuveux : le port

// Définition du protocole LDAP utilisé (LDAP version 3).
ldap_set_option($ldap_con, LDAP_OPT_PROTOCOL_VERSION, 3);


// Tentative de liaison à l'annuaire LDAP en utilisant les informations d'identification fournies ($ldap_dn et $ldap_password).
if (@ldap_bind($ldap_con, $ldap_dn, $ldap_password)) {
    // Si la liaison est réussie, afficher un message de confirmation.
    echo "Connexion LDAP réussie";
    $justthese = array("cn");

    $result = ldap_list($ldap_con, $dn, "cn=*", $justthese);
    echo ("result: ");
    var_dump($result);
    // Boucle sur les entrées pour afficher les informations sur les personnes.
    $info = ldap_get_entries($ldap_con, $result);
    var_dump($info);
    for ($i = 0; $i < $info["count"]; $i++) {
        echo $info[$i]["ou"][0];
    }
} else {
    // Si la liaison échoue, afficher un message d'erreur.
    echo "Echec de la connexion LDAP";
}

// Fermeture de la connexion LDAP.
ldap_close($ldap_con);
