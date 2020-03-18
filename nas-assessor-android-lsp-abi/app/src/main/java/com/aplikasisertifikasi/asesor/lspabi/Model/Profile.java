package com.aplikasisertifikasi.asesor.lspabi.Model;

import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;

import java.util.ArrayList;

public class Profile {
    @SerializedName("user_id")
    @Expose
    private String userId;
    @SerializedName("group_id")
    @Expose
    private String groupId;
    @SerializedName("role_code")
    @Expose
    private String roleCode;
    @SerializedName("role_name")
    @Expose
    private String roleName;
    @SerializedName("registration_number")
    @Expose
    private String registrationNumber;
    @SerializedName("username")
    @Expose
    private String username;
    @SerializedName("email")
    @Expose
    private String email;
    @SerializedName("full_name")
    @Expose
    private String fullName;
    @SerializedName("first_name")
    @Expose
    private String firstName;
    @SerializedName("last_name")
    @Expose
    private String lastName;
    @SerializedName("gender_code")
    @Expose
    private String genderCode;
    @SerializedName("contact")
    @Expose
    private String contact;
    @SerializedName("picture")
    @Expose
    private String picture;
    @SerializedName("address")
    @Expose
    private String address;
    @SerializedName("image_b64")
    @Expose
    private String imageBase64;
    @SerializedName("date_of_birth")
    @Expose
    private String dateOfBirth;
    @SerializedName("m_date_of_birth")
    @Expose
    private String mDateOfBirth;
    @SerializedName("place_of_birth")
    @Expose
    private String placeOfBirth;
    @SerializedName("signature")
    @Expose
    private String signature;
    @SerializedName("signature_flag")
    @Expose
    private Integer signatureFlag;
    @SerializedName("nik")
    @Expose
    private String nik;
    @SerializedName("npwp")
    @Expose
    private String npwp;
    @SerializedName("npwp_photo")
    @Expose
    private String npwpPhoto;
    @SerializedName("integrity_pact_flag")
    @Expose
    private Integer integrityFlag;
    @SerializedName("longitude")
    @Expose
    private String longitude;
    @SerializedName("latitude")
    @Expose
    private String latitude;
    @SerializedName("certificate")
    @Expose
    private String sertifikatBnsp;
    @SerializedName("level")
    String levelManagement;
    @SerializedName("permission")
    private ArrayList<UserPermission> userPermissions;

    public String getSertifikatBnsp() {
        return sertifikatBnsp;
    }

    public void setSertifikatBnsp(String sertifikatBnsp) {
        this.sertifikatBnsp = sertifikatBnsp;
    }

    public String getNpwpPhoto() {
        return npwpPhoto;
    }

    public void setNpwpPhoto(String npwpPhoto) {
        this.npwpPhoto = npwpPhoto;
    }

    public String getImageBase64() {
        return imageBase64;
    }

    public void setImageBase64(String imageBase64) {
        this.imageBase64 = imageBase64;
    }

    public String getUserId() {
        return userId;
    }

    public void setUserId(String userId) {
        this.userId = userId;
    }

    public String getGroupId() {
        return groupId;
    }

    public void setGroupId(String groupId) {
        this.groupId = groupId;
    }

    public String getRoleCode() {
        return roleCode;
    }

    public void setRoleCode(String roleCode) {
        this.roleCode = roleCode;
    }

    public String getRoleName() {
        return roleName;
    }

    public void setRoleName(String roleName) {
        this.roleName = roleName;
    }

    public String getUsername() {
        return username;
    }

    public void setUsername(String username) {
        this.username = username;
    }

    public String getEmail() {
        return email;
    }

    public void setEmail(String email) {
        this.email = email;
    }

    public String getFullName() {
        return fullName;
    }

    public void setFullName(String fullName) {
        this.fullName = fullName;
    }

    public String getFirstName() {
        return firstName;
    }

    public void setFirstName(String firstName) {
        this.firstName = firstName;
    }

    public String getLastName() {
        return lastName;
    }

    public void setLastName(String lastName) {
        this.lastName = lastName;
    }

    public String getGenderCode() {
        return genderCode;
    }

    public void setGenderCode(String genderCode) {
        this.genderCode = genderCode;
    }

    public String getContact() {
        return contact;
    }

    public void setContact(String contact) {
        this.contact = contact;
    }

    public String getPicture() {
        return picture;
    }

    public void setPicture(String picture) {
        this.picture = picture;
    }

    public String getAddress() {
        return address;
    }

    public void setAddress(String address) {
        this.address = address;
    }

    public String getDateOfBirth() {
        return dateOfBirth;
    }

    public void setDateOfBirth(String dateOfBirth) {
        this.dateOfBirth = dateOfBirth;
    }

    public String getPlaceOfBirth() {
        return placeOfBirth;
    }

    public void setPlaceOfBirth(String placeOfBirth) {
        this.placeOfBirth = placeOfBirth;
    }

    public String getSignature() {
        return signature;
    }

    public void setSignature(String signature) {
        this.signature = signature;
    }

    public Integer getSignatureFlag() {
        return signatureFlag;
    }

    public void setSignatureFlag(Integer signatureFlag) {
        this.signatureFlag = signatureFlag;
    }

    public String getNik() {
        return nik;
    }

    public void setNik(String nik) {
        this.nik = nik;
    }

    public String getNpwp() {
        return npwp;
    }

    public void setNpwp(String npwp) {
        this.npwp = npwp;
    }

    public Integer getIntegrityFlag() {
        return integrityFlag;
    }

    public void setIntegrityFlag(Integer integrityFlag) {
        this.integrityFlag = integrityFlag;
    }

    public String getLongitude() {
        return longitude;
    }

    public void setLongitude(String longitude) {
        this.longitude = longitude;
    }

    public String getLatitude() {
        return latitude;
    }

    public void setLatitude(String latitude) {
        this.latitude = latitude;
    }

    public String getmDateOfBirth() {
        return mDateOfBirth;
    }

    public void setmDateOfBirth(String mDateOfBirth) {
        this.mDateOfBirth = mDateOfBirth;
    }

    public ArrayList<UserPermission> getUserPermissions() {
        return userPermissions;
    }

    public void setUserPermissions(ArrayList<UserPermission> userPermissions) {
        this.userPermissions = userPermissions;
    }

    public String getRegistrationNumber() {
        return registrationNumber;
    }

    public void setRegistrationNumber(String registrationNumber) {
        this.registrationNumber = registrationNumber;
    }

    public String getLevelManagement() {
        return levelManagement;
    }

    public void setLevelManagement(String levelManagement) {
        this.levelManagement = levelManagement;
    }
}
