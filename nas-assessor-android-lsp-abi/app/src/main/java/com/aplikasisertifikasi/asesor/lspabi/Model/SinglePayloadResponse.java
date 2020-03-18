package com.aplikasisertifikasi.asesor.lspabi.Model;

import com.google.gson.annotations.SerializedName;

public class SinglePayloadResponse<V> extends APIResponse {

    @SerializedName("data")
    private V v;

    public V getPayload() {
        return v;
    }

    public void setPayload(V v) {
        this.v = v;
    }
}
