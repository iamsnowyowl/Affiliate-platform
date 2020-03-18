package com.aplikasisertifikasi.asesor.lspabi.Model;

public class Authentication {
    String username_email, password;

    public Authentication(String username_email, String password) {
        this.username_email = username_email;
        this.password = password;
    }

    public String getUsername_email() {

        return username_email;
    }

    public void setUsername_email(String username_email) {
        this.username_email = username_email;
    }

    public String getPassword() {
        return password;
    }

    public void setPassword(String password) {
        this.password = password;
    }
}
