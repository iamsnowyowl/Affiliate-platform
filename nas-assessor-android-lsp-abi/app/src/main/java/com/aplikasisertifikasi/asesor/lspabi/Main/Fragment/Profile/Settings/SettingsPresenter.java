package com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.Profile.Settings;

import android.content.Context;

public class SettingsPresenter implements SettingsContract.Presenter {
    SettingsContract.View view;
    Context context;

    public SettingsPresenter(SettingsContract.View view, Context context) {
        this.view = view;
        this.context = context;
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
