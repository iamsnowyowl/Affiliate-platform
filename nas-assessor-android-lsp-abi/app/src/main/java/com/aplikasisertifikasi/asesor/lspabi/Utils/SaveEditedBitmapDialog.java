package com.aplikasisertifikasi.asesor.lspabi.Utils;

import android.app.AlertDialog;
import android.app.Dialog;
import android.content.Intent;
import android.graphics.Bitmap;
import android.graphics.Matrix;
import android.os.Bundle;
import android.os.Environment;
import android.support.annotation.NonNull;
import android.support.design.widget.TextInputEditText;
import android.support.v4.app.DialogFragment;
import android.text.Editable;
import android.text.TextWatcher;
import android.util.Base64;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.ImageView;

import java.io.ByteArrayOutputStream;
import java.io.File;
import java.io.IOException;

import butterknife.BindView;
import butterknife.ButterKnife;

import com.aplikasisertifikasi.asesor.lspabi.Model.Profile;
import com.aplikasisertifikasi.asesor.lspabi.R;
import com.aplikasisertifikasi.asesor.lspabi.Retrofit.CallbackListener;
import com.aplikasisertifikasi.asesor.lspabi.RxJava.UserRepository;

import static android.app.Activity.RESULT_OK;

public class SaveEditedBitmapDialog extends DialogFragment {

    private SaveEditedBitmapDialog.OnSaveEditedBitmapListener onSaveEditedBitmapListener;
    Bitmap mPreviewBitmap, rotated;
    String encodeBase64;
    private String mPreviewFormat, signature_name;
    UserRepository userRepository = new UserRepository();

    int bitmap_size = 100; // image quality 1 - 100;

    @BindView(R.id.edit_capture_preview)
    ImageView imageView;
    @BindView(R.id.edit_file_name)
    TextInputEditText textInputEditText;

    public SaveEditedBitmapDialog() {
    }

    public static SaveEditedBitmapDialog newInstance() {
        return new SaveEditedBitmapDialog();
    }

    @NonNull
    @Override
    public Dialog onCreateDialog(Bundle savedInstanceState) {
        View view = LayoutInflater.from(getContext())
                .inflate(R.layout.layout_save_edited_bitmap_dialog, null);
        ButterKnife.bind(this, view);
        Bundle bundle = getActivity().getIntent().getExtras();
        signature_name = bundle.getString("nama");

        final File filePath = Environment.getExternalStorageDirectory();
        final String[] fileName = {signature_name + "." + mPreviewFormat.toLowerCase()};


        if (rotated != null)
            imageView.setImageBitmap(rotated);
        else
            imageView.setImageResource(R.color.colorAccent);
        textInputEditText.setText(fileName[0]);

        textInputEditText.addTextChangedListener(new TextWatcher() {
            @Override
            public void beforeTextChanged(CharSequence charSequence, int i, int i1, int i2) {
            }

            @Override
            public void afterTextChanged(Editable editable) {
            }

            @Override
            public void onTextChanged(CharSequence charSequence, int i, int i1, int i2) {
                fileName[0] = charSequence.toString();
            }
        });

        AlertDialog.Builder builder = new AlertDialog.Builder(getContext())
                .setView(view)
                .setPositiveButton("Save", (dialogInterface, i) -> {
                    try {
                        if (!fileName[0].contains("."))
                            fileName[0] = fileName[0] + "." + mPreviewFormat.toLowerCase();
                        textInputEditText.setText(fileName[0]);

                        File image = new File(filePath + File.separator + fileName[0]);
                        image.createNewFile();

                        ByteArrayOutputStream bytes = new ByteArrayOutputStream();
                        rotated.compress(Bitmap.CompressFormat.PNG, bitmap_size, bytes);
                        encodeBase64 = Base64.encodeToString(bytes.toByteArray(), Base64.DEFAULT);

                        Profile profile = new Profile();
                        profile.setSignature(encodeBase64);

                        userRepository.updateProfile(profile, new CallbackListener<Profile>() {
                            @Override
                            public void onCompleted() {
                            }

                            @Override
                            public void onCompleted(Profile profile) {
                            }

                            @Override
                            public void onError(Throwable throwable) {

                            }
                        });

                        Intent intent = new Intent();
                        intent.putExtra("encoded", encodeBase64);
                        getActivity().setResult(RESULT_OK, intent);
                        getActivity().finish();

                        if (onSaveEditedBitmapListener != null)
                            onSaveEditedBitmapListener.onSaveEditedBitmapCompleted();
                    } catch (IOException e) {
                        e.printStackTrace();
                    }
                    dismiss();
                })
                .setNegativeButton("Cancel", (dialogInterface, i) -> {
                    if (onSaveEditedBitmapListener != null)
                        onSaveEditedBitmapListener.onSaveEditedBitmapCanceled();
                    dismiss();
                });

        return builder.create();
    }

    // METHODS
    public void setPreviewBitmap(Bitmap bitmap) {
        this.mPreviewBitmap = bitmap;
        Matrix matrix = new Matrix();
        matrix.postRotate(270);
        rotated = Bitmap.createBitmap(mPreviewBitmap, 0, 0, mPreviewBitmap.getWidth(), mPreviewBitmap.getHeight(), matrix, true);
    }

    public void setPreviewFormat(String previewFormat) {
        this.mPreviewFormat = previewFormat;
    }

    // LISTENER
    public void setOnSaveEditedBitmapListener(SaveEditedBitmapDialog.OnSaveEditedBitmapListener onSaveEditedBitmapListener) {
        this.onSaveEditedBitmapListener = onSaveEditedBitmapListener;
    }

    public interface OnSaveEditedBitmapListener {
        void onSaveEditedBitmapCompleted();

        void onSaveEditedBitmapCanceled();
    }

}