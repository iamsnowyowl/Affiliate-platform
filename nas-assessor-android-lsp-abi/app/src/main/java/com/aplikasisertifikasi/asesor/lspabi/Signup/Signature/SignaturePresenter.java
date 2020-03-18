package com.aplikasisertifikasi.asesor.lspabi.Signup.Signature;

public class SignaturePresenter implements SignatureContract.Presenter {
    SignatureContract.View view;

    SignaturePresenter(SignatureContract.View view) {
        this.view = view;
    }

    @Override
    public void load(Object o) {

    }

    @Override
    public void start() {
        view.initViews();
    }

    @Override
    public void end() {

    }
}
