package com.aplikasisertifikasi.asesor.lspabi.Utils;

import android.app.Activity;

import com.karumi.dexter.Dexter;
import com.karumi.dexter.listener.multi.MultiplePermissionsListener;

public class DexterUtils {

    public static void setPermissions(Activity activity, MultiplePermissionsListener multiplePermissionsListener, String... permissions) {
        Dexter.withActivity(activity)
                .withPermissions(permissions)
                .withListener(multiplePermissionsListener)
                .onSameThread()
                .check();
    }
}
