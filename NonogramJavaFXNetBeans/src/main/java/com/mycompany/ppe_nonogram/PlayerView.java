package com.mycompany.ppe_nonogram;

import javafx.geometry.Insets;
import javafx.geometry.Pos;
import javafx.scene.Scene;
import javafx.scene.control.Alert;
import javafx.scene.control.Button;
import javafx.scene.control.Label;
import javafx.scene.layout.VBox;
import javafx.stage.Stage;

public class PlayerView {

    private final User user;

    public PlayerView(User user) {
        this.user = user;
    }

    public void show(Stage stage) {
        Label welcome = new Label("Bienvenue " + user.getUsername());
        Label role = new Label("Rôle : " + user.getRole());
        Label mail = new Label("Mail : " + user.getMail());
        Label monnaie = new Label("Monnaie : " + user.getMonnaie());

        Button logout = new Button("Déconnexion");
        logout.setOnAction(e -> {
            try {
                new MainApp().start(stage);
            } catch (Exception ex) {
                Alert alert = new Alert(Alert.AlertType.ERROR);
                alert.setTitle("Erreur");
                alert.setHeaderText(null);
                alert.setContentText("Impossible de revenir à l'écran d'authentification.");
                alert.showAndWait();
            }
        });

        VBox root = new VBox(15, welcome, role, mail, monnaie, logout);
        root.setAlignment(Pos.CENTER);
        root.setPadding(new Insets(20));

        stage.setTitle("Espace joueur");
        stage.setScene(new Scene(root, 500, 300));
        stage.show();
    }
}
