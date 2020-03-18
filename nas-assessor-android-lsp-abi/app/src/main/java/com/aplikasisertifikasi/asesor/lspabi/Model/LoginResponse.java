package com.aplikasisertifikasi.asesor.lspabi.Model;

import com.google.gson.annotations.SerializedName;

public class LoginResponse<V> extends APIResponse {
    @SerializedName("secret_key")
    private String secretKey;
    @SerializedName("data")
    private V v;

    public LoginResponse(String secretKey) {
        this.secretKey = secretKey;
    }

    public V getV() {
        return v;
    }

    public void setV(V v) {
        this.v = v;
    }

    public String getSecretKey() {
        return secretKey;
    }

    public void setSecretKey(String secretKey) {
        this.secretKey = secretKey;
    }
}
