package com.aplikasisertifikasi.asesor.lspabi.Signup;

import android.app.DatePickerDialog;
import android.content.Intent;
import android.support.design.widget.Snackbar;
import android.util.Log;
import android.view.View;
import android.widget.EditText;
import android.widget.RadioButton;
import android.widget.RadioGroup;
import android.widget.TextView;

import com.github.javiersantos.materialstyleddialogs.MaterialStyledDialog;

import org.greenrobot.eventbus.Subscribe;

import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.Locale;

import butterknife.BindView;
import butterknife.OnClick;

import com.aplikasisertifikasi.asesor.lspabi.Entity.AsessorEntity;
import com.aplikasisertifikasi.asesor.lspabi.MVPCore.BaseActivity;
import com.aplikasisertifikasi.asesor.lspabi.Model.ResponseMessage;
import com.aplikasisertifikasi.asesor.lspabi.Preference.LSPUtils;
import com.aplikasisertifikasi.asesor.lspabi.R;
import com.aplikasisertifikasi.asesor.lspabi.Signup.Signature.Signature;
import com.aplikasisertifikasi.asesor.lspabi.Utils.ProgressLoadingBar;

import static android.app.Activity.RESULT_OK;

public class Signup extends BaseActivity implements SignupContract.View {
    @BindView(R.id.createRegistrationNumber)
    EditText registrationNumber;
    @BindView(R.id.createUsername)
    EditText username;
    @BindView(R.id.createEmail)
    EditText email;
    @BindView(R.id.createFirstName)
    EditText firstName;
    @BindView(R.id.createLastName)
    EditText lastName;
    @BindView(R.id.radioGroup)
    RadioGroup jenisKelamin;
    String gender;
    @BindView(R.id.createTelp)
    EditText noTelp;
    @BindView(R.id.create_tempat_lahir)
    EditText tempatLahir;
    @BindView(R.id.signatureStatus)
    TextView signatureStatus;
    @BindView(R.id.create_tgl_lahir)
    TextView tglLahir;

    String encodedSignature, dateOfBirth;
    SimpleDateFormat dateFormatter = new SimpleDateFormat("dd-MM-yyyy", Locale.US);
    SimpleDateFormat sendingFormat = new SimpleDateFormat("yyyy-MM-dd");
    private SignupPresenter presenter = new SignupPresenter(this, this);

    @Override
    protected void onStart() {
        super.onStart();
        presenter.start();
    }

    @Override
    protected void onResume() {
        super.onResume();
        if (encodedSignature == null) {
            signatureStatus.setText(R.string.signature_uncompleted);
            signatureStatus.setVisibility(View.VISIBLE);
        } else {
            signatureStatus.setText(R.string.signature_completed);
            signatureStatus.setVisibility(View.VISIBLE);
        }
    }

    @Override
    protected int getLayoutId() {
        return R.layout.activity_signup;
    }

    @OnClick(R.id.btnSignUp)
    public void onBtnSignup() {
        if (jenisKelamin.getCheckedRadioButtonId() == -1) {

        } else {
            int selected_gender = jenisKelamin.getCheckedRadioButtonId();
            gender = ((RadioButton) findViewById(selected_gender)).getText().toString().trim();
            if (gender.equals("Laki-laki") || gender.equals("Male")) {
                gender = "M";
            } else {
                gender = "F";
            }
        }

        if (presenter.validateSignup(username, registrationNumber, email, firstName, noTelp, gender)) {
            new MaterialStyledDialog.Builder(this)
                    .setIcon(R.drawable.check)
                    .setHeaderColor(R.color.md_yellow_600)
                    .setTitle(R.string.create_account)
                    .setDescription(R.string.confirmation)
                    .setPositiveText(R.string.yes)
                    .onPositive((dialog, which) -> {
                        presenter.createAccount(username.getText().toString(), registrationNumber.getText().toString(), email.getText().toString(), firstName.getText().toString(), lastName.getText().toString(), noTelp.getText().toString(), gender, dateOfBirth, tempatLahir.getText().toString(), encodedSignature);
                    })
                    .setNegativeText(R.string.no)
                    .onNegative((dialog, which) -> dialog.dismiss())
                    .show();
        }
    }

    @OnClick(R.id.createSignature)
    public void onPressed() {
        LSPUtils.setString(AsessorEntity.USER_FIRST_NAME, firstName.getText().toString());
        LSPUtils.setString(AsessorEntity.USER_LAST_NAME, lastName.getText().toString());
        LSPUtils.setString(AsessorEntity.USER_EMAIL, email.getText().toString());
        Intent intent = new Intent(this, Signature.class);
        intent.putExtra("username", username.getText().toString());
        startActivityForResult(intent, 1);
    }

    @OnClick(R.id.pick_date)
    public void onPickDate() {
        Calendar calendar = Calendar.getInstance();
        DatePickerDialog datePickerDialog = new DatePickerDialog(this, (view, year, month, dayOfMonth) -> {
            Calendar newDate = Calendar.getInstance();
            newDate.set(year, month, dayOfMonth);
            tglLahir.setText(dateFormatter.format(newDate.getTime()));
            dateOfBirth = sendingFormat.format(newDate.getTime());
        }, calendar.get(Calendar.YEAR), calendar.get(Calendar.MONTH), calendar.get(Calendar.DAY_OF_MONTH));
        datePickerDialog.show();
    }

    protected void onActivityResult(int requestCode, int resultCode, Intent data) {
        super.onActivityResult(requestCode, resultCode, data);
        if (requestCode == 1) {
            if (resultCode == RESULT_OK) {
                encodedSignature = data.getStringExtra("encoded");
            }
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
        if (responseMessage.getResponseCode() == 409 || responseMessage.getResponseCode() == 400) {
            ProgressLoadingBar.dismiss();
            Snackbar.make(findViewById(R.id.signrelative), responseMessage.getResponseMessage(), Snackbar.LENGTH_SHORT).show();
        }
    }

    @Override
    public void startActivity(Class c) {
        startActivity(new Intent(this, c));
        finish();
    }

    @Override
    public void initViews() {
        firstName.setText(LSPUtils.getString(AsessorEntity.USER_FIRST_NAME));
        lastName.setText(LSPUtils.getString(AsessorEntity.USER_LAST_NAME));
        email.setText(LSPUtils.getString(AsessorEntity.USER_EMAIL));

    }

    @Override
    public void showSnackBar(String message) {
        Snackbar.make(findViewById(R.id.signrelative), message, Snackbar.LENGTH_SHORT).show();
    }

    @Override
    public void showDialog(String title, String message) {
        new MaterialStyledDialog.Builder(this)
                .setIcon(R.drawable.check)
                .setHeaderColor(R.color.md_green_500)
                .setTitle(title)
                .setDescription(message)
                .setPositiveText(R.string.ok)
                .onPositive((dialog, which) -> {
                    onBackPressed();
                    finish();
                })
                .show();
    }


}
