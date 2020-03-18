package com.aplikasisertifikasi.asesor.lspabi.Model;

import com.google.gson.annotations.SerializedName;

public class UserPermission {
    @SerializedName("permission_id")
    String permissionID;
    @SerializedName("module_code")
    String moduleCode;
    @SerializedName("sub_module_code")
    String subModuleCode;

    public String getPermissionID() {
        return permissionID;
    }

    public void setPermissionID(String permissionID) {
        this.permissionID = permissionID;
    }

    public String getModuleCode() {
        return moduleCode;
    }

    public void setModuleCode(String moduleCode) {
        this.moduleCode = moduleCode;
    }

    public String getSubModuleCode() {
        return subModuleCode;
    }

    public void setSubModuleCode(String subModuleCode) {
        this.subModuleCode = subModuleCode;
    }

    @Override
    public String toString() {
        return "UserPermission{" +
                "permissionID='" + permissionID + '\'' +
                ", moduleCode='" + moduleCode + '\'' +
                ", subModuleCode='" + subModuleCode + '\'' +
                '}';
    }
}
