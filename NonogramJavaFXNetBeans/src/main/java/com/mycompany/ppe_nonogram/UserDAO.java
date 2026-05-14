package com.mycompany.ppe_nonogram;



import java.sql.*;
import java.util.ArrayList;
import java.util.List;

public class UserDAO {

    public boolean usernameExiste(String username) {
        String sql = "SELECT id_user FROM user_ WHERE username = ?";

        try (Connection conn = DatabaseConnection.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {

            stmt.setString(1, username);
            ResultSet rs = stmt.executeQuery();
            return rs.next();

        } catch (SQLException e) {
            System.err.println("Erreur usernameExiste : " + e.getMessage());
            return false;
        }
    }

    public boolean mailExiste(String mail) {
        String sql = "SELECT id_user FROM user_ WHERE mail = ?";

        try (Connection conn = DatabaseConnection.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {

            stmt.setString(1, mail);
            ResultSet rs = stmt.executeQuery();
            return rs.next();

        } catch (SQLException e) {
            System.err.println("Erreur mailExiste : " + e.getMessage());
            return false;
        }
    }

    public boolean creerUtilisateur(String username, String password, String mail) {
        if (!isUsernameValide(username) || !isEmailValide(mail) || !isPasswordValide(password)) {
            return false;
        }

        String sql = "INSERT INTO user_ (role, date_connexion, username, password, mail, monnaie) VALUES (?, NOW(), ?, ?, ?, ?)";

        try (Connection conn = DatabaseConnection.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {

            stmt.setString(1, "joueur");
            stmt.setString(2, username);
            stmt.setString(3, PasswordUtils.hashPassword(password));
            stmt.setString(4, mail);
            stmt.setInt(5, 0);

            return stmt.executeUpdate() > 0;

        } catch (SQLException e) {
            System.err.println("Erreur creerUtilisateur : " + e.getMessage());
            return false;
        }
    }

    public boolean creerAdmin(String username, String password, String mail) {
        if (!isUsernameValide(username) || !isEmailValide(mail) || !isPasswordValide(password)) {
            return false;
        }

        String sql = "INSERT INTO user_ (role, date_connexion, username, password, mail, monnaie) VALUES (?, NOW(), ?, ?, ?, ?)";

        try (Connection conn = DatabaseConnection.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {

            stmt.setString(1, "admin");
            stmt.setString(2, username);
            stmt.setString(3, PasswordUtils.hashPassword(password));
            stmt.setString(4, mail);
            stmt.setInt(5, 1000);

            return stmt.executeUpdate() > 0;

        } catch (SQLException e) {
            System.err.println("Erreur creerAdmin : " + e.getMessage());
            return false;
        }
    }

    public User connecterUtilisateur(String username, String password) {
        String sql = "SELECT id_user, role, username, password, mail, monnaie FROM user_ WHERE username = ?";

        try (Connection conn = DatabaseConnection.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {

            stmt.setString(1, username);
            ResultSet rs = stmt.executeQuery();

            if (rs.next()) {
                String hash = rs.getString("password");

                if (PasswordUtils.verifyPassword(password, hash)) {
                    User user = new User();
                    user.setIdUser(rs.getInt("id_user"));
                    user.setRole(rs.getString("role"));
                    user.setUsername(rs.getString("username"));
                    user.setMail(rs.getString("mail"));
                    user.setMonnaie(rs.getInt("monnaie"));

                    mettreAJourDateConnexion(username);
                    return user;
                }
            }

        } catch (SQLException e) {
            System.err.println("Erreur connecterUtilisateur : " + e.getMessage());
        }

        return null;
    }

    public void mettreAJourDateConnexion(String username) {
        String sql = "UPDATE user_ SET date_connexion = NOW() WHERE username = ?";

        try (Connection conn = DatabaseConnection.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {

            stmt.setString(1, username);
            stmt.executeUpdate();

        } catch (SQLException e) {
            System.err.println("Erreur date_connexion : " + e.getMessage());
        }
    }

    public List<User> getAllUsers() {
        List<User> users = new ArrayList<>();
        String sql = "SELECT id_user, role, username, mail, monnaie FROM user_ ORDER BY id_user ASC";

        try (Connection conn = DatabaseConnection.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql);
             ResultSet rs = stmt.executeQuery()) {

            while (rs.next()) {
                users.add(new User(
                        rs.getInt("id_user"),
                        rs.getString("role"),
                        rs.getString("username"),
                        rs.getString("mail"),
                        rs.getInt("monnaie")
                ));
            }

        } catch (SQLException e) {
            System.err.println("Erreur getAllUsers : " + e.getMessage());
        }

        return users;
    }

    public boolean supprimerUtilisateur(int idUser) {
        String sql = "DELETE FROM user_ WHERE id_user = ?";

        try (Connection conn = DatabaseConnection.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {

            stmt.setInt(1, idUser);
            return stmt.executeUpdate() > 0;

        } catch (SQLException e) {
            System.err.println("Erreur supprimerUtilisateur : " + e.getMessage());
            return false;
        }
    }

    public boolean modifierMonnaie(int idUser, int nouvelleMonnaie) {
        String sql = "UPDATE user_ SET monnaie = ? WHERE id_user = ?";

        try (Connection conn = DatabaseConnection.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {

            stmt.setInt(1, nouvelleMonnaie);
            stmt.setInt(2, idUser);
            return stmt.executeUpdate() > 0;

        } catch (SQLException e) {
            System.err.println("Erreur modifierMonnaie : " + e.getMessage());
            return false;
        }
    }

    private boolean isEmailValide(String mail) {
        return mail != null && mail.matches("^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\\.[A-Za-z]{2,}$");
    }

    private boolean isPasswordValide(String password) {
        return password != null && password.matches("^(?=.*[a-z])(?=.*[A-Z])(?=.*\\d).{8,}$");
    }

    private boolean isUsernameValide(String username) {
        return username != null && username.trim().length() >= 3;
    }
}