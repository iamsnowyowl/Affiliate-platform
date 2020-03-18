package com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.Profile.Settings.PrivacyPolicies;

import android.content.Context;

public class PrivacyPoliciesPresenter implements PrivacyPoliciesContract.Presenter {
    PrivacyPoliciesContract.View view;
    Context context;

    public PrivacyPoliciesPresenter(PrivacyPoliciesContract.View view, Context context) {
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
