package com.mycompany.ppe_nonogram;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;

public class DatabaseConnection {

    private static final String URL = "jdbc:mysql://localhost:3306/nonogram?useSSL=false&serverTimezone=UTC";
    private static final String USER = "test";
    private static final String PASSWORD = "test";

    private DatabaseConnection() {
    }

    public static Connection getConnection() throws SQLException {
        return DriverManager.getConnection(URL, USER, PASSWORD);
    }
}
