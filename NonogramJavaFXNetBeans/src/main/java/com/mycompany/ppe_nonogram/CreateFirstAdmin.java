package com.mycompany.ppe_nonogram;

public class CreateFirstAdmin {

    public static void main(String[] args) {
        UserDAO dao = new UserDAO();

        boolean ok = dao.creerAdmin("admin", "admin123", "admin@mail.com");

        if (ok) {
            System.out.println("Admin créé avec succès.");
        } else {
            System.out.println("Impossible de créer l'admin.");
        }
    }
}
