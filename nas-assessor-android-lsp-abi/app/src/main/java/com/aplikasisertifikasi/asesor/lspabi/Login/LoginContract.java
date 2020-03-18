package com.aplikasisertifikasi.asesor.lspabi.Login;

import android.content.Context;

import com.aplikasisertifikasi.asesor.lspabi.MVPCore.BasePresenter;
import com.aplikasisertifikasi.asesor.lspabi.MVPCore.ExtraView;

public interface LoginContract {

    interface View extends ExtraView {
        void showSnackBar(String message);

        void showToast(String message);
    }

    interface Presenter extends BasePresenter {
        void authLogin(String username, String password, Context context);

        void sendFCMToken(String fcmToken);
    }
}
