package com.aplikasisertifikasi.asesor.lspabi.Utils;

import android.app.Dialog;
import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;

import com.aplikasisertifikasi.asesor.lspabi.R;

import butterknife.BindView;
import butterknife.ButterKnife;

public class DialogWithDescription {
    @BindView(R.id.dialog_descriptions)
    public EditText dialogDescription;
    @BindView(R.id.dialog_title)
    TextView dialogTitle;
    @BindView(R.id.dialog_positive_button)
    Button positiveButton;
    @BindView(R.id.dialog_negative_button)
    Button negativeButton;
    @BindView(R.id.dialog_text_descriptions)
    TextView dialogTextDesc;
    Dialog dialog;
    LayoutInflater inflater;
    View dialogView;
    boolean visibility = false;

    public DialogWithDescription(Context context) {
//        super(context);
        dialog = new Dialog(context);
        inflater = (LayoutInflater) context.getSystemService(context.LAYOUT_INFLATER_SERVICE);
        dialogView = inflater.inflate(R.layout.dialog_with_comment, null);
        dialog.setContentView(dialogView);
        dialog.setCancelable(true);
        ButterKnife.bind(this, dialogView);
    }

    public void setDialogTitle(String titleDialog) {
        dialogTitle.setText(titleDialog);
    }

    public void setDialogDescriptionHint(String descHint) {
        dialogDescription.setVisibility(View.VISIBLE);
        dialogDescription.setHint(descHint);
    }

    public void setPositiveButtonText(String positiveButtonText) {
        positiveButton.setText(positiveButtonText);
    }

    public void setDialogTextDesc(String textDesc) {
        dialogTextDesc.setVisibility(View.VISIBLE);
        dialogTextDesc.setText(textDesc);
    }

    public String getDialogDescription() {
        return dialogDescription.getText().toString();
    }

    public void positiveButton(View.OnClickListener onClickListener) {
        positiveButton.setOnClickListener(onClickListener);
    }

    public void setNegativeButtonText(String negativeButtonText) {
        negativeButton.setText(negativeButtonText);
    }

    public void negativeButton(View.OnClickListener onClickListener) {
        negativeButton.setOnClickListener(onClickListener);
    }

    public void dismiss() {
        dialog.dismiss();
    }

    public void showDialog() {
        dialog.show();
    }
}
