package com.mycompany.ppe_nonogram;



import org.mindrot.jbcrypt.BCrypt;

public class PasswordUtils {

    private PasswordUtils() {
    }

    public static String hashPassword(String password) {
        return BCrypt.hashpw(password, BCrypt.gensalt(10));
    }

    public static boolean verifyPassword(String password, String hashedPassword) {
        if (hashedPassword == null || !hashedPassword.startsWith("$2")) {
            return false;
        }
        return BCrypt.checkpw(password, hashedPassword);
    }
}