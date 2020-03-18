package com.aplikasisertifikasi.asesor.lspabi.Model;

import android.support.annotation.NonNull;

import com.google.gson.annotations.SerializedName;

import java.io.Serializable;

public class LSPObject implements Serializable {

    @NonNull
//    @PrimaryKey
//    @ColumnInfo(name="_id")
    @SerializedName("id")
    private String id;

//    @ColumnInfo(name="name")
    @SerializedName("name")
    private String name;

    public String getId() {
        return id;
    }

    public void setId(String id) {
        this.id = id;
    }

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

}
