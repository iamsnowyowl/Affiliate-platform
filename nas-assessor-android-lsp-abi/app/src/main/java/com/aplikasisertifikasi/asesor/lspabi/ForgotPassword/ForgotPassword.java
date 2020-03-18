package com.aplikasisertifikasi.asesor.lspabi.ForgotPassword;

import android.content.Intent;
import android.support.design.widget.Snackbar;
import android.util.Log;
import android.widget.EditText;
import android.widget.LinearLayout;

import com.aplikasisertifikasi.asesor.lspabi.MVPCore.BaseActivity;
import com.aplikasisertifikasi.asesor.lspabi.Model.ResponseMessage;
import com.aplikasisertifikasi.asesor.lspabi.R;

import com.github.javiersantos.materialstyleddialogs.MaterialStyledDialog;

import org.greenrobot.eventbus.Subscribe;

import butterknife.BindView;
import butterknife.OnClick;
import com.aplikasisertifikasi.asesor.lspabi.Utils.ProgressLoadingBar;

public class ForgotPassword extends BaseActivity implements ForgotPassContract.View {

    ForgotPassPresenter presenter = new ForgotPassPresenter(this, this);
    @BindView(R.id.forgotEmail)
    EditText email;
    @BindView(R.id.linearForgotPass)
    LinearLayout layout;

    @Override
    protected void onStart() {
        super.onStart();
        presenter.start();
    }

    @OnClick(R.id.btnResetPass)
    public void forgotPassClick() {
        if (presenter.validate(email)) {
            presenter.sendEmail(email.getText().toString());
        }
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
    }

    @Subscribe
    public void onResponse(ResponseMessage responseMessage) {
    }

    @Override
    public void startActivity(Class c) {
        startActivity(new Intent(this, c));
    }

    @Override
    public void initViews() {
        Log.d("START", "Start this activity");
    }

    @Override
    protected int getLayoutId() {
        return R.layout.activity_forgot_password;
    }

    @Override
    public void showMaterialDialog(String message) {
        new MaterialStyledDialog.Builder(this)
                .setTitle(R.string.success)
                .setDescription(message)
                .setIcon(R.drawable.check)
                .setHeaderColor(R.color.md_green_500)
                .setPositiveText(R.string.ok)
                .onPositive((dialog, which) -> {
                    dialog.dismiss();
                    onBackPressed();
                    finish();
                })
                .show();
    }

    @Override
    public void showSnackbar(String message) {
        Snackbar.make(layout, message, Snackbar.LENGTH_SHORT).show();
    }
}
