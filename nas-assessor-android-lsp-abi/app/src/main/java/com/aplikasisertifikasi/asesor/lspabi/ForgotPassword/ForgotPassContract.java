package com.aplikasisertifikasi.asesor.lspabi.ForgotPassword;


import com.aplikasisertifikasi.asesor.lspabi.MVPCore.BasePresenter;
import com.aplikasisertifikasi.asesor.lspabi.MVPCore.ExtraView;

public interface ForgotPassContract {

    interface View extends ExtraView {
        void showMaterialDialog(String message);
        void showSnackbar(String message);
    }

    interface Presenter extends BasePresenter {
        void sendEmail(String email);
    }
}
