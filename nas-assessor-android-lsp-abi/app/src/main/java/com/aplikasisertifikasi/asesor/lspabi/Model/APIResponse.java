package com.aplikasisertifikasi.asesor.lspabi.Model;

import com.google.gson.annotations.SerializedName;

public class APIResponse {

    @SerializedName("apiVersion")
    private String apiVersion;
    @SerializedName("requestTime")
    private Integer requestTime;
    @SerializedName("responseStatus")
    private String responseStatus;
    @SerializedName("error")
    private ResponseMessage responseMessage;

    public String getApiVersion() {
        return apiVersion;
    }

    public void setApiVersion(String apiVersion) {
        this.apiVersion = apiVersion;
    }

    public Integer getRequestTime() {
        return requestTime;
    }

    public void setRequestTime(Integer requestTime) {
        this.requestTime = requestTime;
    }

    public String getResponseStatus() {
        return responseStatus;
    }

    public void setResponseStatus(String responseStatus) {
        this.responseStatus = responseStatus;
    }
}
