package com.aplikasisertifikasi.asesor.lspabi.Model;

public class DigestAuthentication {
    private String authorization;
    private String date;

    public DigestAuthentication(String authorization, String date){
        this.authorization = authorization;
        this.date = date;
    }

    public String getAuthorization()
    {
        return this.authorization;
    }

    public String getDate()
    {
        return this.date;
    }
}
