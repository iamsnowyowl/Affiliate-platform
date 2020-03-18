package com.aplikasisertifikasi.asesor.lspabi.Services;

public class ConnectivityEvent {
    String message;
    boolean isConnected;

    public ConnectivityEvent(String message, boolean isConnected) {
        this.message = message;
        this.isConnected = isConnected;
    }

    public String getMessage() {
        return message;
    }

    public void setMessage(String message) {
        this.message = message;
    }

    public boolean isConnected() {
        return isConnected;
    }

    public void setConnected(boolean connected) {
        isConnected = connected;
    }
}
