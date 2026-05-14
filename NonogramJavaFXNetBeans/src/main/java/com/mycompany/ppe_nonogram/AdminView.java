package com.mycompany.ppe_nonogram;

import javafx.beans.property.SimpleIntegerProperty;
import javafx.beans.property.SimpleStringProperty;
import javafx.collections.FXCollections;
import javafx.geometry.Insets;
import javafx.scene.Scene;
import javafx.scene.control.Alert;
import javafx.scene.control.Button;
import javafx.scene.control.Label;
import javafx.scene.control.TableColumn;
import javafx.scene.control.TableView;
import javafx.scene.control.TextInputDialog;
import javafx.scene.layout.HBox;
import javafx.scene.layout.VBox;
import javafx.stage.Stage;

public class AdminView {

    private final User admin;
    private final UserDAO userDAO = new UserDAO();
    private final TableView<User> table = new TableView<>();

    public AdminView(User admin) {
        this.admin = admin;
    }

    public void show(Stage stage) {
        Label title = new Label("Espace administrateur - " + admin.getUsername());

        TableColumn<User, Number> colId = new TableColumn<>("ID");
        colId.setCellValueFactory(data -> new SimpleIntegerProperty(data.getValue().getIdUser()));

        TableColumn<User, String> colRole = new TableColumn<>("Rôle");
        colRole.setCellValueFactory(data -> new SimpleStringProperty(data.getValue().getRole()));

        TableColumn<User, String> colUsername = new TableColumn<>("Username");
        colUsername.setCellValueFactory(data -> new SimpleStringProperty(data.getValue().getUsername()));

        TableColumn<User, String> colMail = new TableColumn<>("Mail");
        colMail.setCellValueFactory(data -> new SimpleStringProperty(data.getValue().getMail()));

        TableColumn<User, Number> colMonnaie = new TableColumn<>("Monnaie");
        colMonnaie.setCellValueFactory(data -> new SimpleIntegerProperty(data.getValue().getMonnaie()));

        table.getColumns().setAll(colId, colRole, colUsername, colMail, colMonnaie);
        table.setColumnResizePolicy(TableView.CONSTRAINED_RESIZE_POLICY_FLEX_LAST_COLUMN);

        Button btnRefresh = new Button("Actualiser");
        Button btnDelete = new Button("Supprimer");
        Button btnMonnaie = new Button("Modifier monnaie");
        Button btnLogout = new Button("Déconnexion");

        btnRefresh.setOnAction(e -> chargerUtilisateurs());

        btnDelete.setOnAction(e -> {
            User selected = table.getSelectionModel().getSelectedItem();
            if (selected == null) {
                alert(Alert.AlertType.WARNING, "Sélection", "Sélectionne un utilisateur.");
                return;
            }

            if (selected.getIdUser() == admin.getIdUser()) {
                alert(Alert.AlertType.WARNING, "Action refusée", "Tu ne peux pas supprimer ton propre compte.");
                return;
            }

            boolean ok = userDAO.supprimerUtilisateur(selected.getIdUser());
            if (ok) {
                alert(Alert.AlertType.INFORMATION, "Succès", "Utilisateur supprimé.");
                chargerUtilisateurs();
            } else {
                alert(Alert.AlertType.ERROR, "Erreur", "Suppression impossible.");
            }
        });

        btnMonnaie.setOnAction(e -> {
            User selected = table.getSelectionModel().getSelectedItem();
            if (selected == null) {
                alert(Alert.AlertType.WARNING, "Sélection", "Sélectionne un utilisateur.");
                return;
            }

            TextInputDialog dialog = new TextInputDialog(String.valueOf(selected.getMonnaie()));
            dialog.setTitle("Modifier monnaie");
            dialog.setHeaderText(null);
            dialog.setContentText("Nouvelle monnaie pour " + selected.getUsername() + " :");

            dialog.showAndWait().ifPresent(value -> {
                try {
                    int nouvelleMonnaie = Integer.parseInt(value);
                    boolean ok = userDAO.modifierMonnaie(selected.getIdUser(), nouvelleMonnaie);

                    if (ok) {
                        alert(Alert.AlertType.INFORMATION, "Succès", "Monnaie mise à jour.");
                        chargerUtilisateurs();
                    } else {
                        alert(Alert.AlertType.ERROR, "Erreur", "Modification impossible.");
                    }
                } catch (NumberFormatException ex) {
                    alert(Alert.AlertType.ERROR, "Erreur", "Valeur invalide.");
                }
            });
        });

        btnLogout.setOnAction(e -> {
            try {
                new MainApp().start(stage);
            } catch (Exception ex) {
                alert(Alert.AlertType.ERROR, "Erreur", "Impossible de revenir à l'écran d'authentification.");
            }
        });

        HBox buttons = new HBox(10, btnRefresh, btnMonnaie, btnDelete, btnLogout);
        VBox root = new VBox(15, title, table, buttons);
        root.setPadding(new Insets(15));

        chargerUtilisateurs();

        stage.setTitle("Administration");
        stage.setScene(new Scene(root, 750, 450));
        stage.show();
    }

    private void chargerUtilisateurs() {
        table.setItems(FXCollections.observableArrayList(userDAO.getAllUsers()));
    }

    private void alert(Alert.AlertType type, String title, String message) {
        Alert alert = new Alert(type);
        alert.setTitle(title);
        alert.setHeaderText(null);
        alert.setContentText(message);
        alert.showAndWait();
    }
}
