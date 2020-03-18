package com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.Profile.Settings;

import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;

import com.aplikasisertifikasi.asesor.lspabi.MVPCore.BaseActivity;
import com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.Profile.Settings.PrivacyPolicies.PrivacyPoliciesActivity;
import com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.Profile.Settings.TermConditions.TermConditionsActivity;
import com.aplikasisertifikasi.asesor.lspabi.Main.MainActivity;
import com.aplikasisertifikasi.asesor.lspabi.Preference.LSPUtils;
import com.aplikasisertifikasi.asesor.lspabi.R;
import com.aplikasisertifikasi.asesor.lspabi.Utils.MyUtils;

import butterknife.BindView;
import butterknife.OnClick;

public class SettingsActivity extends BaseActivity implements SettingsContract.View {

    SettingsPresenter presenter = new SettingsPresenter(this, this);
    String currentLanguage, currentLang;
    @BindView(R.id.btn_indonesia)
    Button btnIndonesia;
    @BindView(R.id.btn_english)
    Button btnEnglish;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        presenter.start();

        currentLanguage = LSPUtils.getString(currentLang);

        if (LSPUtils.getString(currentLang).equals("en")) {
            btnEnglish.setBackground(getResources().getDrawable(R.drawable.round_button));
            btnEnglish.setTextColor(getResources().getColor(R.color.md_white_1000));
        } else {
            btnIndonesia.setBackground(getResources().getDrawable(R.drawable.round_button));
            btnIndonesia.setTextColor(getResources().getColor(R.color.md_white_1000));
        }
    }

    @Override
    protected int getLayoutId() {
        return R.layout.activity_settings;
    }

    //change language
    @OnClick(value = {
            R.id.btn_english,
            R.id.btn_indonesia
    })
    public void changeLanguage(View view) {
        if (view.getId() == R.id.btn_indonesia) {
            setBahasa("id");
            btnIndonesia.setBackground(getResources().getDrawable(R.drawable.round_button));
            btnIndonesia.setTextColor(getResources().getColor(R.color.md_white_1000));
            btnEnglish.setBackground(getResources().getDrawable(R.drawable.round_white));
            btnEnglish.setTextColor(getResources().getColor(R.color.md_black_1000));
        } else {
            setBahasa("en");
            btnEnglish.setBackground(getResources().getDrawable(R.drawable.round_button));
            btnEnglish.setTextColor(getResources().getColor(R.color.md_white_1000));
            btnIndonesia.setBackground(getResources().getDrawable(R.drawable.round_white));
            btnIndonesia.setTextColor(getResources().getColor(R.color.md_black_1000));
        }
    }

    @OnClick(R.id.btn_close)
    public void close() {
        onBackPressed();
    }

    //change language
    public void setBahasa(String localeName) {
        if (!localeName.equals(LSPUtils.getString(currentLang))) {
            MyUtils.changeLanguage(this, localeName);
            Intent refresh = new Intent(this, MainActivity.class);
            LSPUtils.setString(currentLang, localeName);
            refresh.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
            startActivity(refresh);
            finish();
        }
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
    public void startActivity(Class<?> c) {

    }

    @Override
    public void initViews() {

    }

    public void TermConditions(View view) {
        startActivity(new Intent(this, TermConditionsActivity.class));
    }

    public void PrivacyPolicy(View view) {
        startActivity(new Intent(this, PrivacyPoliciesActivity.class));
    }
}
