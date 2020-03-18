package com.aplikasisertifikasi.asesor.lspabi.Model;

import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;

import java.io.Serializable;

public class ScheduleModel extends LSPObject implements Serializable {
    @SerializedName("produk")
    @Expose
    private String produk;
    @SerializedName("waktu_mulai")
    @Expose
    private String waktu_mulai;

    public ScheduleModel(String produk, String waktu_mulai) {
        this.produk = produk;
        this.waktu_mulai = waktu_mulai;
    }

    public String getProduk() {
        return produk;
    }

    public void setProduk(String produk) {
        this.produk = produk;
    }

    public String getWaktu_mulai() {
        return waktu_mulai;
    }

    public void setWaktu_mulai(String waktu_mulai) {
        this.waktu_mulai = waktu_mulai;
    }
}
