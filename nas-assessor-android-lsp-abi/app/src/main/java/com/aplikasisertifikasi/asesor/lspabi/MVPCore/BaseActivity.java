package com.aplikasisertifikasi.asesor.lspabi.MVPCore;

import android.annotation.SuppressLint;
import android.content.Context;
import android.content.Intent;
import android.content.IntentFilter;
import android.os.Bundle;
import android.support.annotation.NonNull;
import android.support.v7.app.AppCompatActivity;
import android.util.Log;
import android.view.View;
import android.view.inputmethod.InputMethodManager;

import com.afollestad.materialdialogs.DialogAction;
import com.afollestad.materialdialogs.MaterialDialog;
import com.aplikasisertifikasi.asesor.lspabi.Core.LSPApplication;
import com.aplikasisertifikasi.asesor.lspabi.Login.Login;
import com.aplikasisertifikasi.asesor.lspabi.Model.ResponseMessage;
import com.aplikasisertifikasi.asesor.lspabi.Preference.LSPUtils;
import com.github.javiersantos.materialstyleddialogs.MaterialStyledDialog;

import org.greenrobot.eventbus.EventBus;
import org.greenrobot.eventbus.Subscribe;
import org.greenrobot.eventbus.ThreadMode;

import com.aplikasisertifikasi.asesor.lspabi.R;

import butterknife.ButterKnife;

import com.aplikasisertifikasi.asesor.lspabi.Services.ConnectivityEvent;
import com.aplikasisertifikasi.asesor.lspabi.Utils.NetworkManager;

public abstract class BaseActivity extends AppCompatActivity {
    NetworkManager networkManager = new NetworkManager();
    IntentFilter intentFilter = new IntentFilter("android.net.conn.CONNECTIVITY_CHANGE");
    MaterialStyledDialog materialStyledDialog;

    @SuppressLint("ResourceAsColor")
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(getLayoutId());
        ButterKnife.bind(this);

        materialStyledDialog = new MaterialStyledDialog.Builder(this)
                .setTitle("Tidak ada koneksi internet")
                .setDescription("Periksa jaring internet anda")
                .setHeaderColor(R.color.md_red_500)
                .setIcon(R.drawable.lost_connection)
                .setCancelable(true)
                .setPositiveText("Oke")
                .onPositive((dialog, which) -> dialog.dismiss())
                .show();
    }

    @Override
    protected void onStart() {
        super.onStart();
        EventBus.getDefault().register(this);
    }

    @Override
    protected void onResume() {
        super.onResume();
        registerReceiver(networkManager, intentFilter);
    }

    protected abstract int getLayoutId();

    @Subscribe
    public void onNetworkStatus(ConnectivityEvent connectivityEvent) {
        if (!connectivityEvent.isConnected()) {
            materialStyledDialog.show();
        } else {
            materialStyledDialog.dismiss();
            Log.d("CONNECTION", connectivityEvent.getMessage());
        }
    }

    @Subscribe(threadMode = ThreadMode.MAIN)
    public void onEvent(ResponseMessage event) {
        materialStyledDialog = new MaterialStyledDialog.Builder(this)
                .setTitle("Oops!")
                .setDescription(event.getResponseMessage())
                .setHeaderColor(R.color.md_red_500)
                .setIcon(R.drawable.error)
                .setCancelable(false)
                .setPositiveText("Oke")
                .onPositive(new MaterialDialog.SingleButtonCallback() {
                    @Override
                    public void onClick(@NonNull MaterialDialog dialog, @NonNull DialogAction which) {
                        dialog.dismiss();
                        LSPUtils.logout();
                        Intent intent = new Intent(LSPApplication.getAppContext(), Login.class);
                        intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
                        intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TASK);
                        LSPApplication.getAppContext().startActivity(intent);
                    }
                })
                .show();
    }

    @Override
    protected void onPause() {
        super.onPause();
        unregisterReceiver(networkManager);
    }

    @Override
    protected void onStop() {
        super.onStop();
        EventBus.getDefault().unregister(this);
    }

    public void hideKeyboard() {
        View view = this.getCurrentFocus();
        if (view != null) {
            InputMethodManager imm = (InputMethodManager)
                    getSystemService(Context.INPUT_METHOD_SERVICE);
            imm.hideSoftInputFromWindow(view.getWindowToken(), 0);
        }
    }
}
