package com.aplikasisertifikasi.asesor.lspabi.Utils;

import android.content.Context;

import com.kaopiz.kprogresshud.KProgressHUD;

public class ProgressLoadingBar {

    private static KProgressHUD kProgressHUD;

    public static void show(Context context) {
        kProgressHUD = KProgressHUD.create(context);

        kProgressHUD.setStyle(KProgressHUD.Style.SPIN_INDETERMINATE)
                .setCancellable(false)
                .setLabel("Please Wait...")
                .setAnimationSpeed(2)
                .setDimAmount(0.5f)
                .show();
    }

    public static void dismiss() {
        if (kProgressHUD.isShowing())
            kProgressHUD.dismiss();
    }
}
