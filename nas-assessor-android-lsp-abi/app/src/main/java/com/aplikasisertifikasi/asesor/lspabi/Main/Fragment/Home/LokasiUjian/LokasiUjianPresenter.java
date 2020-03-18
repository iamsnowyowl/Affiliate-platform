package com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.Home.LokasiUjian;

public class LokasiUjianPresenter implements LokasiUjianContract.Presenter {
    private LokasiUjianContract.View view;

    public LokasiUjianPresenter(LokasiUjianContract.View view) {
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
