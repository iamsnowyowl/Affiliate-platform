package com.aplikasisertifikasi.asesor.lspabi.Core;

import android.app.Application;
import android.content.Context;

import com.miguelbcr.ui.rx_paparazzo2.RxPaparazzo;
import com.crashlytics.android.Crashlytics;
import io.fabric.sdk.android.Fabric;

public class LSPApplication extends Application {
    //    private static MODatabase moDatabase;
    private static Context context;

    @Override
    public void onCreate() {
        super.onCreate();
        Fabric.with(this, new Crashlytics());

        LSPApplication.context = getApplicationContext();

        RxPaparazzo.register(this)
                .withFileProviderPath("Sertimedia");
//        moDatabase = MODatabase.getInstance(this);
    }

    public static Context getAppContext() {
        return LSPApplication.context;
    }

//    public static MODatabase getMODatabase() {
//        return moDatabase;
//    }
}
