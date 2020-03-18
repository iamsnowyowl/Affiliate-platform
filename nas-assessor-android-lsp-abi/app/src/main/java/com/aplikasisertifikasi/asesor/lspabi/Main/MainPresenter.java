package com.aplikasisertifikasi.asesor.lspabi.Main;

public class MainPresenter implements MainContract.Presenter {

    MainContract.View view;

    public MainPresenter(MainContract.View view) {
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
