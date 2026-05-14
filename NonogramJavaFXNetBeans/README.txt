Projet NetBeans / Maven JavaFX pour authentification Nonogram.

Fichiers inclus :
- JavaFX + Maven
- Connexion / Inscription
- BCrypt
- Espace admin
- Espace joueur
- DAO connecté à la table user_

Avant de lancer :
1. Ouvre le projet dans NetBeans.
2. Vérifie ton JDK (idéalement Java 21).
3. Modifie DatabaseConnection.java :
   - USER = ton utilisateur MySQL
   - PASSWORD = ton mot de passe MySQL
4. Vérifie que la base s'appelle bien nonogram.
5. Vérifie que la table s'appelle bien user_.

Pour créer le premier admin :
- Lance la classe CreateFirstAdmin.java une seule fois.

Pour lancer l'application :
- Lance MainApp.java
ou
- utilise la commande Maven : mvn javafx:run

Important :
- Les mots de passe sont stockés avec BCrypt.
- Les anciens mots de passe en clair dans la base ne fonctionneront pas.
