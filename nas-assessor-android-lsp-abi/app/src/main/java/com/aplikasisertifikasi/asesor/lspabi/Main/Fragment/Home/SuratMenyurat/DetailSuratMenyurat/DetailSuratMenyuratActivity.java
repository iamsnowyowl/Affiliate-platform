package com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.Home.SuratMenyurat.DetailSuratMenyurat;

import android.os.Build;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.webkit.CookieManager;
import android.webkit.CookieSyncManager;
import android.webkit.WebView;
import android.webkit.WebViewClient;
import android.widget.LinearLayout;
import android.widget.Toast;

import java.util.HashMap;
import java.util.Map;

import butterknife.BindView;
import butterknife.OnClick;

import com.aplikasisertifikasi.asesor.lspabi.Entity.AssessmentEntity;
import com.aplikasisertifikasi.asesor.lspabi.Entity.ResourceEntity;
import com.aplikasisertifikasi.asesor.lspabi.Entity.RoleEntity;
import com.aplikasisertifikasi.asesor.lspabi.MVPCore.BaseActivity;
import com.aplikasisertifikasi.asesor.lspabi.Model.AssessmentLetters;
import com.aplikasisertifikasi.asesor.lspabi.Preference.LSPUtils;
import com.aplikasisertifikasi.asesor.lspabi.R;
import com.aplikasisertifikasi.asesor.lspabi.Utils.DialogWithDescription;
import com.aplikasisertifikasi.asesor.lspabi.Utils.ProgressLoadingBar;

public class DetailSuratMenyuratActivity extends BaseActivity implements DetailSuratMenyuratContract.View {
    @BindView(R.id.surat_menyurat_webview)
    WebView webView;
    AssessmentLetters assessmentLetters = new AssessmentLetters();
    DetailSuratMenyuratPresenter presenter = new DetailSuratMenyuratPresenter(this);

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        presenter.start();
    }

    @Override
    protected int getLayoutId() {
        return R.layout.activity_detail_surat_menyurat;
    }

    @Override
    public void showLoadingView() {
        ProgressLoadingBar.show(this);
    }

    @Override
    public void dismissLoadingView() {
        ProgressLoadingBar.dismiss();
    }

    @Override
    public void errorLoadingView() {
        Toast.makeText(this, R.string.lost_connection, Toast.LENGTH_SHORT).show();
    }

    @Override
    public void startActivity(Class<?> c) {

    }

    @Override
    public void initViews() {
        String webSource = getIntent().getStringExtra(ResourceEntity.WEB_SOURCE);

        CookieSyncManager.createInstance(this);
        CookieManager cookieManager = CookieManager.getInstance();
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.LOLLIPOP) {
            cookieManager.removeAllCookies(null);
        }
        cookieManager.setAcceptCookie(false);

        webView.loadUrl(webSource);//sementara belum pakai digest
        webView.clearCache(true);
        webView.setWebViewClient(new WebViewClient());
        webView.clearHistory();
        webView.getSettings().setBuiltInZoomControls(true);
        webView.getSettings().setDisplayZoomControls(false);
        webView.getSettings().setSupportZoom(true);
        webView.setInitialScale(150);
        webView.getSettings().setJavaScriptEnabled(true);
        webView.getSettings().setSaveFormData(false);
        webView.getSettings().setUserAgentString("okhttp/3.10.0");
        webView.getSettings().setLoadsImagesAutomatically(true);

    }

    @Override
    public void assignCompleted() {
        finish();
    }
}
