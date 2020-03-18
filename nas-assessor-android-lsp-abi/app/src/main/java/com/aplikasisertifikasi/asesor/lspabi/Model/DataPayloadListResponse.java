package com.aplikasisertifikasi.asesor.lspabi.Model;

import com.google.gson.annotations.SerializedName;

import java.util.List;

public class DataPayloadListResponse<V> extends APIResponse {

    @SerializedName("data")
    private List<V> vList;

    public List<V> getPayloadList() {
        return vList;
    }

    public void setPayloadList(List<V> vList) {
        this.vList = vList;
    }
}
