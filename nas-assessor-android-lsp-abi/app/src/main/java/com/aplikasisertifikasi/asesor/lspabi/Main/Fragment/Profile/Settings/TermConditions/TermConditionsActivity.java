package com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.Profile.Settings.TermConditions;

import android.os.Build;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.View;
import android.webkit.CookieManager;
import android.webkit.CookieSyncManager;
import android.webkit.WebView;
import android.webkit.WebViewClient;
import android.widget.ImageView;

import com.aplikasisertifikasi.asesor.lspabi.MVPCore.BaseActivity;
import com.aplikasisertifikasi.asesor.lspabi.R;

import butterknife.BindView;

public class TermConditionsActivity extends BaseActivity implements TermConditionsContract.View {

    TermConditionsPresenter presenter = new TermConditionsPresenter(this, this);
    @BindView(R.id.webview_termconditions)
    WebView webView;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        presenter.start();
    }

    @Override
    protected int getLayoutId() {
        return R.layout.activity_term_conditions;
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
        CookieSyncManager.createInstance(this);
        CookieManager cookieManager = CookieManager.getInstance();
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.LOLLIPOP) {
            cookieManager.removeAllCookies(null);
        }
        cookieManager.setAcceptCookie(false);

        webView.loadUrl("https://sertimedia.com/term-and-condition.html");//sementara belum pakai digest
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

    public void goBack(View view) {
        onBackPressed();
    }
}
