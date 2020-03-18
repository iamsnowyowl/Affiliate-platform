package com.aplikasisertifikasi.asesor.lspabi.SplashScreen;

import android.content.Intent;
import android.os.Bundle;
import android.os.Handler;
import android.support.v7.app.AppCompatActivity;
import android.util.Log;
import android.view.Window;

import com.aplikasisertifikasi.asesor.lspabi.Login.Login;
import com.aplikasisertifikasi.asesor.lspabi.Main.MainActivity;
import com.aplikasisertifikasi.asesor.lspabi.Preference.LSPUtils;
import com.aplikasisertifikasi.asesor.lspabi.R;
import com.aplikasisertifikasi.asesor.lspabi.Utils.MyUtils;

import java.util.Locale;

public class SplashScreen extends AppCompatActivity {

    LSPUtils lspUtils = new LSPUtils();
    String currentLang;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        requestWindowFeature(Window.FEATURE_NO_TITLE);
        setContentView(R.layout.activity_splash_screen);

        LSPUtils.setString(currentLang, Locale.getDefault().getLanguage());
        MyUtils.changeLanguage(this, LSPUtils.getString((currentLang)));


        final Handler handler = new Handler();
        handler.postDelayed(() -> {
            if (lspUtils.isLogin() && LSPUtils.getSecretKey() != null) {
                startActivity(new Intent(SplashScreen.this, MainActivity.class));
            } else if (lspUtils.isLogin()) {
                startActivity(new Intent(SplashScreen.this, Login.class));
            } else {
                startActivity(new Intent(SplashScreen.this, Login.class));
            }
            finish();
        }, 1000);
    }
}
