package com.mycompany.ppe_nonogram;

public class User {

    private int idUser;
    private String role;
    private String username;
    private String mail;
    private int monnaie;

    public User() {
    }

    public User(int idUser, String role, String username, String mail, int monnaie) {
        this.idUser = idUser;
        this.role = role;
        this.username = username;
        this.mail = mail;
        this.monnaie = monnaie;
    }

    public int getIdUser() {
        return idUser;
    }

    public void setIdUser(int idUser) {
        this.idUser = idUser;
    }

    public String getRole() {
        return role;
    }

    public void setRole(String role) {
        this.role = role;
    }

    public String getUsername() {
        return username;
    }

    public void setUsername(String username) {
        this.username = username;
    }

    public String getMail() {
        return mail;
    }

    public void setMail(String mail) {
        this.mail = mail;
    }

    public int getMonnaie() {
        return monnaie;
    }

    public void setMonnaie(int monnaie) {
        this.monnaie = monnaie;
    }
}
