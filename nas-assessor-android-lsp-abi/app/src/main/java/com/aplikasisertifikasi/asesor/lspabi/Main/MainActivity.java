package com.aplikasisertifikasi.asesor.lspabi.Main;

import android.annotation.SuppressLint;
import android.app.NotificationChannel;
import android.app.NotificationManager;
import android.os.Build;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentManager;
import android.support.v4.app.FragmentTransaction;
import android.util.Log;

import com.crashlytics.android.Crashlytics;
import com.google.firebase.FirebaseApp;
import com.google.firebase.iid.FirebaseInstanceId;
import com.google.firebase.messaging.FirebaseMessaging;
import com.roughike.bottombar.BottomBar;
import com.roughike.bottombar.BottomBarTab;

import org.greenrobot.eventbus.Subscribe;

import butterknife.BindView;
import io.fabric.sdk.android.Fabric;

import com.aplikasisertifikasi.asesor.lspabi.MVPCore.BaseActivity;
import com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.History.History;
import com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.Home.Home;
import com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.Profile.ProfileActivity;
import com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.Schedule.Schedule;
import com.aplikasisertifikasi.asesor.lspabi.Model.ResponseMessage;
import com.aplikasisertifikasi.asesor.lspabi.Preference.FirebaseUtils;
import com.aplikasisertifikasi.asesor.lspabi.R;

public class MainActivity extends BaseActivity implements MainContract.View {

    @BindView(R.id.bottom_navigation)
    BottomBar bottomBar;
    Fragment fragment;
    FragmentManager fragmentManager;
    MainPresenter presenter = new MainPresenter(this);
    FirebaseUtils firebaseUtils = new FirebaseUtils();

    @SuppressLint("ResourceAsColor")
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        presenter.start();
    }

    @Override
    protected int getLayoutId() {
        return R.layout.activity_main;
    }

    @Subscribe
    public void onResponse(ResponseMessage responseMessage) {

    }

    @Override
    public void showLoadingView() {
    }

    @Override
    public void dismissLoadingView() {
    }

    @Override
    public void errorLoadingView() {
    }

    @Override
    public void startActivity(Class c) {
    }

    @Override
    public void initViews() {
        Fabric.with(this, new Crashlytics());
        fragmentManager = getSupportFragmentManager();
        bottomBar.setOnTabSelectListener(tabId -> {
            switch (tabId) {
                case R.id.tab_home:
                    fragment = new Home();
                    break;
                case R.id.tab_history:
                    fragment = new History();
                    break;
                case R.id.tab_schedule:
                    fragment = new Schedule();
                    break;
                case R.id.tab_profile:
                    fragment = new ProfileActivity();
                    break;
            }
            final FragmentTransaction transaction = fragmentManager.beginTransaction();
            transaction.replace(R.id.main_container, fragment);
            transaction.commit();
        });

        bottomBar.setOnTabReselectListener(tabId -> {
            if (tabId == R.id.tab_home) {
                fragment = new Home();
            }
        });

        BottomBarTab badge = bottomBar.getTabWithId(R.id.tab_home);
//        badge.setBadgeCount(4);

        if (android.os.Build.VERSION.SDK_INT >= Build.VERSION_CODES.O) {
            NotificationManager notificationManager = getSystemService(NotificationManager.class);
            notificationManager.createNotificationChannel(new NotificationChannel("default", "lsp-accessor", NotificationManager.IMPORTANCE_LOW));
        }

        FirebaseApp.initializeApp(this);
//        FirebaseMessaging.getInstance().subscribeToTopic("news");
    }
}