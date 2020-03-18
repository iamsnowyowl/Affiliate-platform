package com.aplikasisertifikasi.asesor.lspabi.Signup;


import com.aplikasisertifikasi.asesor.lspabi.MVPCore.BasePresenter;
import com.aplikasisertifikasi.asesor.lspabi.MVPCore.ExtraView;

public interface SignupContract {
    interface View extends ExtraView {
        void showSnackBar(String message);
        void showDialog(String title, String message);
    }

    interface Presenter extends BasePresenter {
        void createAccount(String username, String registrtionNumber, String email, String firstName, String lastName, String contact, String gender, String dateOfBirth, String placeOfBirth, String signature);
    }
}
