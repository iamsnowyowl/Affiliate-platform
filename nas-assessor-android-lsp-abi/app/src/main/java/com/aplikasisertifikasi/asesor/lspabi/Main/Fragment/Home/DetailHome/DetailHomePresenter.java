package com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.Home.DetailHome;

public class DetailHomePresenter implements DetailHomeContract.Presenter {
    private DetailHomeContract.View view;

    public DetailHomePresenter(DetailHomeContract.View view) {
        this.view = view;
    }

    @Override
    public void load(Object o) {

    }

    @Override
    public void start() {
        view.initViews();
        view.initFragmentListener();
        view.determineFragment();
    }

    @Override
    public void end() {

    }

    @Override
    public void asessmenProcess(String process) {

    }
}
