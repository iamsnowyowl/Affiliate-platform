package com.aplikasisertifikasi.asesor.lspabi.Signup.Signature;

import android.Manifest;
import android.annotation.SuppressLint;
import android.content.pm.PackageManager;
import android.graphics.Bitmap;
import android.os.Build;
import android.os.Handler;
import android.support.annotation.NonNull;
import android.support.design.widget.FloatingActionButton;
import android.support.design.widget.Snackbar;
import android.support.v4.app.ActivityCompat;
import android.support.v4.content.ContextCompat;
import android.os.Bundle;
import android.view.View;
import android.view.animation.OvershootInterpolator;

import com.byox.drawview.enums.DrawingCapture;
import com.byox.drawview.views.DrawView;

import butterknife.BindView;
import butterknife.OnClick;
import com.aplikasisertifikasi.asesor.lspabi.MVPCore.BaseActivity;
import com.aplikasisertifikasi.asesor.lspabi.R;
import com.aplikasisertifikasi.asesor.lspabi.Utils.AnimateUtils;
import com.aplikasisertifikasi.asesor.lspabi.Utils.SaveBitmapDialog;

public class Signature extends BaseActivity implements SignatureContract.View {

    private final int STORAGE_PERMISSIONS = 1000;
    private final int STORAGE_PERMISSIONS2 = 2000;

    SignaturePresenter presenter = new SignaturePresenter(this);
    @BindView(R.id.draw_view)
    DrawView signature_drawer;
    @BindView(R.id.fab_clear)
    FloatingActionButton clear_signature;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        presenter.start();
    }

    @Override
    protected int getLayoutId() {
        return R.layout.activity_signature;
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

    @OnClick(R.id.btn_save_signature)
    public void onSave() {
        requestPermissions(0);
    }

    @Override
    public void onRequestPermissionsResult(int requestCode, @NonNull String[] permissions, @NonNull int[] grantResults) {
        switch (requestCode) {
            case STORAGE_PERMISSIONS:
                if (grantResults.length > 0
                        && grantResults[0] == PackageManager.PERMISSION_GRANTED) {
                    new Handler().postDelayed(() -> saveDraw(), 600);
                }
                break;
        }
    }

    @SuppressLint("ResourceAsColor")
    @Override
    public void initViews() {
        signature_drawer.setDrawWidth(12);
        signature_drawer.setOnDrawViewListener(new DrawView.OnDrawViewListener() {
            @Override
            public void onStartDrawing() {
            }

            @Override
            public void onEndDrawing() {
                if (clear_signature.getVisibility() == View.INVISIBLE)
                    AnimateUtils.ScaleInAnimation(clear_signature, 50, 300, new OvershootInterpolator(), true);
            }

            @Override
            public void onClearDrawing() {
                if (clear_signature.getVisibility() == View.VISIBLE)
                    AnimateUtils.ScaleInAnimation(clear_signature, 50, 300, new OvershootInterpolator(), true);
            }

            @Override
            public void onRequestText() {
            }

            @Override
            public void onAllMovesPainted() {
                new Handler().postDelayed(() -> {
                    if (!signature_drawer.isDrawViewEmpty())
                        clear_signature.setVisibility(View.VISIBLE);
                }, 300);
            }
        });

        clear_signature.setOnClickListener(v -> signature_drawer.restartDrawing());
    }

    private void requestPermissions(int option) {
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.M) {
            if (option == 0) {
                if (ContextCompat.checkSelfPermission(Signature.this,
                        Manifest.permission.READ_EXTERNAL_STORAGE) != PackageManager.PERMISSION_GRANTED
                        || ContextCompat.checkSelfPermission(Signature.this,
                        Manifest.permission.WRITE_EXTERNAL_STORAGE) != PackageManager.PERMISSION_GRANTED) {

                    ActivityCompat.requestPermissions(Signature.this,
                            new String[]{
                                    Manifest.permission.READ_EXTERNAL_STORAGE,
                                    Manifest.permission.WRITE_EXTERNAL_STORAGE},
                            STORAGE_PERMISSIONS);
                } else {
                    saveDraw();
                }
            } else if (option == 1) {
                if (ContextCompat.checkSelfPermission(Signature.this,
                        Manifest.permission.READ_EXTERNAL_STORAGE) != PackageManager.PERMISSION_GRANTED
                        || ContextCompat.checkSelfPermission(Signature.this,
                        Manifest.permission.WRITE_EXTERNAL_STORAGE) != PackageManager.PERMISSION_GRANTED) {

                    ActivityCompat.requestPermissions(Signature.this,
                            new String[]{
                                    Manifest.permission.READ_EXTERNAL_STORAGE,
                                    Manifest.permission.WRITE_EXTERNAL_STORAGE},
                            STORAGE_PERMISSIONS2);
                }
            }
        } else {
            if (option == 0)
                saveDraw();
        }
    }

    private void saveDraw() {
        SaveBitmapDialog saveBitmapDialog = SaveBitmapDialog.newInstance();
        Object[] createCaptureResponse = signature_drawer.createCapture(DrawingCapture.BITMAP);
        saveBitmapDialog.setPreviewBitmap((Bitmap) createCaptureResponse[0]);
        saveBitmapDialog.setPreviewFormat(String.valueOf(createCaptureResponse[1]));
        saveBitmapDialog.setOnSaveBitmapListener(new SaveBitmapDialog.OnSaveBitmapListener() {
            @Override
            public void onSaveBitmapCompleted() {
                Snackbar.make(clear_signature, "Capture saved succesfully!", 2000).show();
                finish();
            }

            @Override
            public void onSaveBitmapCanceled() {
                Snackbar.make(clear_signature, "Capture saved canceled.", 2000).show();
            }
        });
        saveBitmapDialog.show(getSupportFragmentManager(), "saveBitmap");
    }
}
