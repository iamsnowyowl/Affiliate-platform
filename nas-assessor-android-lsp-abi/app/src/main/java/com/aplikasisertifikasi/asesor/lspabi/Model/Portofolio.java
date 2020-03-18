package com.aplikasisertifikasi.asesor.lspabi.Model;

import com.google.gson.annotations.SerializedName;

import java.util.List;

public class Portofolio {
    @SerializedName("master_portfolio_id")
    String portofolioId;
    @SerializedName("applicant_id")
    String applicantId;
    @SerializedName("form_type")
    String formType;
    @SerializedName("form_description")
    String formDescription;
    @SerializedName("form_name")
    String formName;
    @SerializedName("applicant_portfolio")
    List<ApplicantPortofolio> applicantPortofolios;
    @SerializedName("persyaratan")
    List<ApplicantPortofolio> persyaratans;


    public String getPortofolioId() {
        return portofolioId;
    }

    public void setPortofolioId(String portofolioId) {
        this.portofolioId = portofolioId;
    }

    public String getFormType() {
        return formType;
    }

    public void setFormType(String formType) {
        this.formType = formType;
    }

    public String getFormDescription() {
        return formDescription;
    }

    public void setFormDescription(String formDescription) {
        this.formDescription = formDescription;
    }

    public String getFormName() {
        return formName;
    }

    public void setFormName(String formName) {
        this.formName = formName;
    }

    public class ApplicantPortofolio {
        @SerializedName("form_value")
        String formValue;
        @SerializedName("filename")
        String fileName;
        @SerializedName("ext")
        String fileExtension;
        @SerializedName("mime_type")
        String mimeType;

        public String getMimeType() {
            return mimeType;
        }

        public void setMimeType(String mimeType) {
            this.mimeType = mimeType;
        }

        public String getFormValue() {
            return formValue;
        }

        public void setFormValue(String formValue) {
            this.formValue = formValue;
        }

        public String getFileExtension() {
            return fileExtension;
        }

        public void setFileExtension(String fileExtension) {
            this.fileExtension = fileExtension;
        }

        public String getFileName() {
            return fileName;
        }

        public void setFileName(String fileName) {
            this.fileName = fileName;
        }
    }

    public List<ApplicantPortofolio> getApplicantPortofolios() {
        return applicantPortofolios;
    }

    public void setApplicantPortofolios(List<ApplicantPortofolio> applicantPortofolios) {
        this.applicantPortofolios = applicantPortofolios;
    }

    public List<ApplicantPortofolio> getPersyaratans() {
        return persyaratans;
    }

    public void setPersyaratans(List<ApplicantPortofolio> persyaratans) {
        this.persyaratans = persyaratans;
    }

    public String getApplicantId() {
        return applicantId;
    }

    public void setApplicantId(String applicantId) {
        this.applicantId = applicantId;
    }
}
