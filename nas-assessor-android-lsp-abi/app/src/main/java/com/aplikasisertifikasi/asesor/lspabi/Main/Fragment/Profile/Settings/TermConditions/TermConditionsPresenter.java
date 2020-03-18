package com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.Profile.Settings.TermConditions;

import android.content.Context;

public class TermConditionsPresenter implements TermConditionsContract.Presenter {
    TermConditionsContract.View view;
    Context context;

    public TermConditionsPresenter(TermConditionsContract.View view, Context context) {
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
