package com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.Profile.EditProfile;

import android.Manifest;
import android.content.pm.PackageManager;
import android.graphics.Bitmap;
import android.os.Build;
import android.os.Handler;
import android.support.annotation.NonNull;
import android.support.design.widget.FloatingActionButton;
import android.support.design.widget.Snackbar;
import android.os.Bundle;
import android.support.v4.app.ActivityCompat;
import android.support.v4.content.ContextCompat;
import android.view.View;
import android.view.animation.OvershootInterpolator;

import com.byox.drawview.enums.DrawingCapture;
import com.byox.drawview.views.DrawView;

import butterknife.BindView;
import butterknife.OnClick;
import com.aplikasisertifikasi.asesor.lspabi.MVPCore.BaseActivity;
import com.aplikasisertifikasi.asesor.lspabi.R;
import com.aplikasisertifikasi.asesor.lspabi.Signup.Signature.Signature;
import com.aplikasisertifikasi.asesor.lspabi.Utils.AnimateUtils;
import com.aplikasisertifikasi.asesor.lspabi.Utils.SaveEditedBitmapDialog;

public class EditSignature extends BaseActivity {
    @BindView(R.id.edit_draw_view)
    DrawView editSignatureDraw;
    @BindView(R.id.edit_fab_clear)
    FloatingActionButton fab_clear;

    private final int STORAGE_PERMISSIONS = 1000;
    private final int STORAGE_PERMISSIONS2 = 2000;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        editSignatureDraw.setDrawWidth(12);
        editSignatureDraw.setOnDrawViewListener(new DrawView.OnDrawViewListener() {
            @Override
            public void onStartDrawing() {
            }

            @Override
            public void onEndDrawing() {
                if (fab_clear.getVisibility() == View.INVISIBLE)
                    AnimateUtils.ScaleInAnimation(fab_clear, 50, 300, new OvershootInterpolator(), true);
            }

            @Override
            public void onClearDrawing() {
                if (fab_clear.getVisibility() == View.VISIBLE)
                    AnimateUtils.ScaleInAnimation(fab_clear, 50, 300, new OvershootInterpolator(), true);
            }

            @Override
            public void onRequestText() {
            }

            @Override
            public void onAllMovesPainted() {
                new Handler().postDelayed(() -> {
                    if (!editSignatureDraw.isDrawViewEmpty())
                        fab_clear.setVisibility(View.VISIBLE);
                }, 300);
            }
        });

        fab_clear.setOnClickListener(v -> editSignatureDraw.restartDrawing());
    }

    @Override
    protected int getLayoutId() {
        return R.layout.activity_edit_signature;
    }

    @OnClick(R.id.btn_save_edit_signature)
    public void onEditSignature(){
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


    private void requestPermissions(int option) {
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.M) {
            if (option == 0) {
                if (ContextCompat.checkSelfPermission(EditSignature.this,
                        Manifest.permission.READ_EXTERNAL_STORAGE) != PackageManager.PERMISSION_GRANTED
                        || ContextCompat.checkSelfPermission(EditSignature.this,
                        Manifest.permission.WRITE_EXTERNAL_STORAGE) != PackageManager.PERMISSION_GRANTED) {

                    ActivityCompat.requestPermissions(EditSignature.this,
                            new String[]{
                                    Manifest.permission.READ_EXTERNAL_STORAGE,
                                    Manifest.permission.WRITE_EXTERNAL_STORAGE},
                            STORAGE_PERMISSIONS);
                } else {
                    saveDraw();
                }
            } else if (option == 1) {
                if (ContextCompat.checkSelfPermission(EditSignature.this,
                        Manifest.permission.READ_EXTERNAL_STORAGE) != PackageManager.PERMISSION_GRANTED
                        || ContextCompat.checkSelfPermission(EditSignature.this,
                        Manifest.permission.WRITE_EXTERNAL_STORAGE) != PackageManager.PERMISSION_GRANTED) {

                    ActivityCompat.requestPermissions(EditSignature.this,
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
        SaveEditedBitmapDialog saveEditedBitmapDialog = SaveEditedBitmapDialog.newInstance();
        Object[] createCaptureResponse = editSignatureDraw.createCapture(DrawingCapture.BITMAP);
        saveEditedBitmapDialog.setPreviewBitmap((Bitmap) createCaptureResponse[0]);
        saveEditedBitmapDialog.setPreviewFormat(String.valueOf(createCaptureResponse[1]));
        saveEditedBitmapDialog.setOnSaveEditedBitmapListener(new SaveEditedBitmapDialog.OnSaveEditedBitmapListener() {
            @Override
            public void onSaveEditedBitmapCompleted() {
                Snackbar.make(fab_clear, "Capture saved succesfully!", 2000).show();
                finish();
            }

            @Override
            public void onSaveEditedBitmapCanceled() {
                Snackbar.make(fab_clear, "Capture saved canceled.", 2000).show();
            }
        });
        saveEditedBitmapDialog.show(getSupportFragmentManager(), "saveBitmap");
    }

}
