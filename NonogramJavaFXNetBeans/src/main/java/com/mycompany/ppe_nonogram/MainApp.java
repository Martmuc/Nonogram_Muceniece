package com.mycompany.ppe_nonogram;


import javafx.application.Application;
import javafx.geometry.Insets;
import javafx.geometry.Pos;
import javafx.scene.Scene;
import javafx.scene.control.*;
import javafx.scene.layout.GridPane;
import javafx.scene.layout.VBox;
import javafx.stage.Stage;

public class MainApp extends Application {

    private final UserDAO userDAO = new UserDAO();

    @Override
    public void start(Stage primaryStage) {
        TabPane tabPane = new TabPane();

        Tab loginTab = new Tab("Connexion");
        loginTab.setClosable(false);
        loginTab.setContent(createLoginPane(primaryStage));

        Tab registerTab = new Tab("Inscription");
        registerTab.setClosable(false);
        registerTab.setContent(createRegisterPane());

        tabPane.getTabs().addAll(loginTab, registerTab);

        Scene scene = new Scene(tabPane, 460, 340);

        primaryStage.setTitle("Nonogram - Authentification");
        primaryStage.setScene(scene);
        primaryStage.show();
    }

    private VBox createLoginPane(Stage stage) {
        GridPane grid = new GridPane();
        grid.setVgap(10);
        grid.setHgap(10);
        grid.setPadding(new Insets(20));
        grid.setAlignment(Pos.CENTER);

        Label lblUsername = new Label("Nom d'utilisateur :");
        TextField txtUsername = new TextField();

        Label lblPassword = new Label("Mot de passe :");
        PasswordField txtPassword = new PasswordField();

        Button btnLogin = new Button("Se connecter");

        grid.add(lblUsername, 0, 0);
        grid.add(txtUsername, 1, 0);
        grid.add(lblPassword, 0, 1);
        grid.add(txtPassword, 1, 1);
        grid.add(btnLogin, 1, 2);

        btnLogin.setOnAction(e -> {
            String username = txtUsername.getText().trim();
            String password = txtPassword.getText();

            if (username.isEmpty() || password.isEmpty()) {
                alert(Alert.AlertType.ERROR, "Erreur", "Veuillez remplir tous les champs.");
                return;
            }

            User user = userDAO.connecterUtilisateur(username, password);

            if (user == null) {
                alert(Alert.AlertType.ERROR, "Erreur", "Identifiants incorrects.");
                return;
            }

            if ("admin".equalsIgnoreCase(user.getRole())) {
                new AdminView(user).show(stage);
            } else {
                new PlayerView(user).show(stage);
            }
        });

        return new VBox(grid);
    }

    private VBox createRegisterPane() {
        GridPane grid = new GridPane();
        grid.setVgap(10);
        grid.setHgap(10);
        grid.setPadding(new Insets(20));
        grid.setAlignment(Pos.CENTER);

        Label lblUsername = new Label("Nom d'utilisateur :");
        TextField txtUsername = new TextField();

        Label lblMail = new Label("Email :");
        TextField txtMail = new TextField();

        Label lblPassword = new Label("Mot de passe :");
        PasswordField txtPassword = new PasswordField();

        Label lblConfirm = new Label("Confirmer :");
        PasswordField txtConfirm = new PasswordField();

        Button btnRegister = new Button("Créer un compte");

        grid.add(lblUsername, 0, 0);
        grid.add(txtUsername, 1, 0);
        grid.add(lblMail, 0, 1);
        grid.add(txtMail, 1, 1);
        grid.add(lblPassword, 0, 2);
        grid.add(txtPassword, 1, 2);
        grid.add(lblConfirm, 0, 3);
        grid.add(txtConfirm, 1, 3);
        grid.add(btnRegister, 1, 4);

        btnRegister.setOnAction(e -> {
            String username = txtUsername.getText().trim();
            String mail = txtMail.getText().trim();
            String password = txtPassword.getText();
            String confirm = txtConfirm.getText();

            if (username.isEmpty() || mail.isEmpty() || password.isEmpty() || confirm.isEmpty()) {
                alert(Alert.AlertType.ERROR, "Erreur", "Veuillez remplir tous les champs.");
                return;
            }

            if (username.length() < 3) {
                alert(Alert.AlertType.ERROR, "Erreur", "Le nom d'utilisateur doit contenir au moins 3 caractères.");
                return;
            }

            if (!isEmailValide(mail)) {
                alert(Alert.AlertType.ERROR, "Erreur", "Adresse email invalide.");
                return;
            }

            if (!isPasswordValide(password)) {
                alert(
                    Alert.AlertType.ERROR,
                    "Erreur",
                    "Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule et un chiffre."
                );
                return;
            }

            if (!password.equals(confirm)) {
                alert(Alert.AlertType.ERROR, "Erreur", "Les mots de passe ne correspondent pas.");
                return;
            }

            if (userDAO.usernameExiste(username)) {
                alert(Alert.AlertType.ERROR, "Erreur", "Nom d'utilisateur déjà utilisé.");
                return;
            }

            if (userDAO.mailExiste(mail)) {
                alert(Alert.AlertType.ERROR, "Erreur", "Email déjà utilisé.");
                return;
            }

            boolean ok = userDAO.creerUtilisateur(username, password, mail);

            if (ok) {
                alert(Alert.AlertType.INFORMATION, "Succès", "Compte créé avec succès.");
                txtUsername.clear();
                txtMail.clear();
                txtPassword.clear();
                txtConfirm.clear();
            } else {
                alert(Alert.AlertType.ERROR, "Erreur", "Création du compte impossible.");
            }
        });

        return new VBox(grid);
    }

    private boolean isEmailValide(String mail) {
        if (mail == null || mail.isBlank()) {
            return false;
        }
        return mail.matches("^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\\.[A-Za-z]{2,}$");
    }

    private boolean isPasswordValide(String password) {
        if (password == null) {
            return false;
        }
        return password.matches("^(?=.*[a-z])(?=.*[A-Z])(?=.*\\d).{8,}$");
    }

    private void alert(Alert.AlertType type, String title, String message) {
        Alert alert = new Alert(type);
        alert.setTitle(title);
        alert.setHeaderText(null);
        alert.setContentText(message);
        alert.showAndWait();
    }

    public static void main(String[] args) {
        launch(args);
    }
}