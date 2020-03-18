package com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.Profile.EditProfile;

import android.Manifest;
import android.app.Activity;
import android.app.DatePickerDialog;
import android.content.Intent;
import android.graphics.Bitmap;
import android.os.Bundle;
import android.support.annotation.NonNull;
import android.support.v7.app.AlertDialog;
import android.text.Editable;
import android.text.TextWatcher;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.RadioButton;
import android.widget.RadioGroup;
import android.widget.RelativeLayout;
import android.widget.ScrollView;
import android.widget.TextView;
import android.widget.Toast;

import com.github.javiersantos.materialstyleddialogs.MaterialStyledDialog;
import com.karumi.dexter.MultiplePermissionsReport;
import com.karumi.dexter.PermissionToken;
import com.karumi.dexter.listener.PermissionRequest;
import com.karumi.dexter.listener.multi.MultiplePermissionsListener;

import org.greenrobot.eventbus.Subscribe;

import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.List;
import java.util.Locale;

import butterknife.BindView;
import butterknife.OnClick;
import de.hdodenhof.circleimageview.CircleImageView;

import com.miguelbcr.ui.rx_paparazzo2.entities.FileData;
import com.aplikasisertifikasi.asesor.lspabi.Entity.AsessorEntity;
import com.aplikasisertifikasi.asesor.lspabi.Entity.RoleEntity;
import com.aplikasisertifikasi.asesor.lspabi.MVPCore.BaseActivity;
import com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.Profile.EditProfile.PaktaIntegritas.EditPaktaIntegritas;
import com.aplikasisertifikasi.asesor.lspabi.Model.Profile;
import com.aplikasisertifikasi.asesor.lspabi.Model.ResponseMessage;
import com.aplikasisertifikasi.asesor.lspabi.Preference.LSPUtils;
import com.aplikasisertifikasi.asesor.lspabi.R;
import com.aplikasisertifikasi.asesor.lspabi.Services.PermissionHelper;
import com.aplikasisertifikasi.asesor.lspabi.Utils.DexterUtils;
import com.aplikasisertifikasi.asesor.lspabi.Utils.MyUtils;
import com.aplikasisertifikasi.asesor.lspabi.Utils.ProgressLoadingBar;

public class EditProfile extends BaseActivity implements EditProfileContract.View {

    @BindView(R.id.edit_img_profile)
    CircleImageView imgEditProfile;
    @BindView(R.id.edit_tgl_lahir)
    TextView editTglLahir;
    @BindView(R.id.layout)
    ScrollView layout;
    @BindView(R.id.edit_no_registrasi)
    EditText no_registrasi;
    @BindView(R.id.edit_nik)
    EditText nik;
    @BindView(R.id.edit_npwp)
    EditText npwp;
    @BindView(R.id.edit_nama)
    EditText nama_asesor;
    @BindView(R.id.edit_email)
    EditText email_asesor;
    @BindView(R.id.edit_address)
    EditText alamat_asesor;
    @BindView(R.id.edit_contact)
    EditText tlp_asesor;
    @BindView(R.id.editTempatLahir)
    EditText tempat_lahir_asesor;
    @BindView(R.id.pick_date)
    ImageView pick_date;
    @BindView(R.id.edit_signature_status)
    TextView signatureStatus;
    //    @BindView(R.id.edit_pakta_status)
//    TextView integrityStatus;
    @BindView(R.id.btn_upload_npwp)
    TextView uploadNPWP;
    @BindView(R.id.edit_sertifikat_bnsp)
    Button sertifikatBnsp;
    //    @BindView(R.id.edit_pakta_integritas)
//    Button integrityPact;
    @BindView(R.id.edit_signature)
    Button signature;
    @BindView(R.id.icon_success_npwp)
    ImageView readyToUplaodNpwp;
    @BindView(R.id.regist_number_container)
    LinearLayout registContainer;
    @BindView(R.id.nik_container)
    LinearLayout nikContainer;
    @BindView(R.id.birth_container)
    LinearLayout birthContainer;
    @BindView(R.id.npwp_container)
    RelativeLayout npwpContainer;
    @BindView(R.id.sertifikat_file)
    TextView sertifikatFileName;
    @BindView(R.id.radioGroupGender)
    RadioGroup radioGroupGender;


    PermissionHelper permissionHelper;
    EditProfilePresenter presenter = new EditProfilePresenter(this, this);
    String encodeBase64, dateOfBirth, encodedSignature, npwpBase64, sertifikatBase64, sertifikatFlag;
    SimpleDateFormat dateFormatter, sendingFormat;

    int signatureFlag;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        permissionHelper = new PermissionHelper(this);

        presenter.start();
        dateFormatter = new SimpleDateFormat("dd MMMM yyyy", Locale.US);
        sendingFormat = new SimpleDateFormat("yyyy-MM-dd");
    }

    @Override
    protected void onResume() {
        super.onResume();
        presenter.getProfile();
    }

    @Override
    protected int getLayoutId() {
        return R.layout.activity_edit_profile;
    }

    @Subscribe
    public void onResponseEvent(ResponseMessage responseMessage) {

    }

    @OnClick(R.id.edit_btn_save)
    public void onSaveEdit() {
        if(nik.getText().length() < 16){
            Toast.makeText(this, R.string.alert_nik, Toast.LENGTH_LONG).show();
        }else{
            new MaterialStyledDialog.Builder(this)
                    .setTitle(R.string.save_profile)
                    .setDescription(R.string.save_profile_confirmation)
                    .setIcon(R.drawable.check)
                    .setHeaderColor(R.color.primaryColorDark)
                    .setPositiveText(R.string.yes)
                    .onPositive((dialog, which) -> {
                        Profile profile = new Profile();
                        if (LSPUtils.getRoleCode().equals(RoleEntity.MANAGEMENT)) {
                            profile.setAddress(alamat_asesor.getText().toString());
                            profile.setContact(tlp_asesor.getText().toString());
                        } else {
                            int selected_gender = radioGroupGender.getCheckedRadioButtonId();
                            String gender = ((RadioButton) findViewById(selected_gender)).getText().toString().trim();
                            if (gender.equals("Laki-laki") || gender.equals("Male")) {
                                gender = "M";
                            } else {
                                gender = "F";
                            }

                            profile.setRegistrationNumber(no_registrasi.getText().toString());
                            profile.setNik(nik.getText().toString());
                            profile.setNpwp(npwp.getText().toString());
                            profile.setNpwpPhoto(npwpBase64);
                            profile.setGenderCode(gender);
                            profile.setSertifikatBnsp(sertifikatBase64);
                            profile.setAddress(alamat_asesor.getText().toString());
                            profile.setContact(tlp_asesor.getText().toString());
                            profile.setDateOfBirth(dateOfBirth);
                            profile.setPlaceOfBirth(tempat_lahir_asesor.getText().toString());
                        }
                        presenter.saveEditProfile(profile);
                    })
                    .setNegativeText(R.string.no)
                    .onNegative((dialog, which) -> dialog.dismiss())
                    .show();
        }
    }

    public void onActivityResult(int requestCode, int resultCode, Intent data) {
        super.onActivityResult(requestCode, resultCode, data);
        if (resultCode == Activity.RESULT_OK) {
            if (requestCode == 11) {
                encodedSignature = data.getStringExtra("encoded");
            }
        }
    }

//    @OnClick(R.id.edit_pakta_integritas)
//    public void onPaktaPressed() {
//        String flag = String.valueOf(integrityFlag);
//        Intent intent = new Intent(this, EditPaktaIntegritas.class);
//        intent.putExtra("integrity_pact", flag);
//        intent.putExtra("signature_flag", String.valueOf(signatureFlag));
//        startActivity(intent);
//    }

    @OnClick(R.id.edit_signature)
    public void onPressed() {
        Intent intent = new Intent(this, EditSignature.class);
        intent.putExtra("nama", nama_asesor.getText().toString());
        startActivityForResult(intent, 11);
    }

    @OnClick(R.id.edit_btn_pick_img)
    public void onInsertImage() {
        checkAndRequestPermissions("profile_pict");
    }

    @OnClick(R.id.pick_date)
    public void onPickDate() {
        Calendar calendar = Calendar.getInstance();
        DatePickerDialog datePickerDialog = new DatePickerDialog(this, (view, year, month, dayOfMonth) -> {
            Calendar newDate = Calendar.getInstance();
            newDate.set(year, month, dayOfMonth);
            editTglLahir.setText(dateFormatter.format(newDate.getTime()));
            dateOfBirth = sendingFormat.format(newDate.getTime());
        }, calendar.get(Calendar.YEAR), calendar.get(Calendar.MONTH), calendar.get(Calendar.DAY_OF_MONTH));
        datePickerDialog.show();
    }

    private void selectImage(String type) {
        DexterUtils.setPermissions(this, new MultiplePermissionsListener() {
            @Override
            public void onPermissionsChecked(MultiplePermissionsReport report) {
                final CharSequence[] items = {getString(R.string.take_photo), getString(R.string.choose_photo),
                        getString(R.string.cancel)};
                AlertDialog.Builder builder = new AlertDialog.Builder(EditProfile.this);
                builder.setTitle(R.string.add_photo);
                builder.setIcon(R.drawable.camera);
                builder.setItems(items, (dialog, item) -> {
                    if (items[item].equals(getString(R.string.take_photo))) {
                        openCamera(type);
                    } else if (items[item].equals(getString(R.string.choose_photo))) {
                        openGalery(type);
                    } else if (items[item].equals(getString(R.string.cancel))) {
                        dialog.dismiss();
                    }
                });
                builder.show();

            }

            @Override
            public void onPermissionRationaleShouldBeShown(List<PermissionRequest> permissions, PermissionToken token) {
                token.continuePermissionRequest();
            }
        }, Manifest.permission.READ_EXTERNAL_STORAGE, Manifest.permission.CAMERA, Manifest.permission.WRITE_EXTERNAL_STORAGE);
    }

    private void openGalery(String type) {
        presenter.takePictFromGalery(this, type);
    }

    private void openCamera(String type) {
        presenter.takePictFromCamera(this, type);
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

    @Override
    public void startActivity(Class c) {
        Intent intent = new Intent(this, c);
        startActivity(intent);
        finish();
    }

    @Override
    public void initViews() {
        if (LSPUtils.getRoleCode().equals(RoleEntity.MANAGEMENT)) {
            sertifikatBnsp.setVisibility(View.GONE);
            registContainer.setVisibility(View.GONE);
            nikContainer.setVisibility(View.GONE);
            npwpContainer.setVisibility(View.GONE);
            birthContainer.setVisibility(View.GONE);
        }

        nik.addTextChangedListener(new TextWatcher() {
            @Override
            public void beforeTextChanged(CharSequence charSequence, int i, int i1, int i2) {

            }

            @Override
            public void onTextChanged(CharSequence charSequence, int i, int i1, int i2) {
                if (nik.length() < 16) {
                    nik.setError(getString(R.string.nik_must_16));
                } else {
                    nik.setError(null);
                }
            }

            @Override
            public void afterTextChanged(Editable editable) {

            }
        });

        nama_asesor.setText(LSPUtils.getString(AsessorEntity.USER_FULL_NAME));
        email_asesor.setText(LSPUtils.getString(AsessorEntity.USER_EMAIL));
        alamat_asesor.setText(LSPUtils.getString(AsessorEntity.USER_ADDRESS));
        tlp_asesor.setText(LSPUtils.getString(AsessorEntity.USER_CONTACT));
        try {
            editTglLahir.setText(MyUtils.dateFormatter("yyyy-MM-dd", LSPUtils.getString(AsessorEntity.USER_DATE_BIRTH), "dd MMMM yyyy"));
        } catch (ParseException e) {
            e.printStackTrace();
        }
        tempat_lahir_asesor.setText(LSPUtils.getString(AsessorEntity.USER_PLACE_BIRTH));
    }

    private boolean checkAndRequestPermissions(String type) {
        permissionHelper.permissionListener(() -> selectImage(type));
        permissionHelper.checkAndRequestPermissions();
        return true;
    }

    @Override
    public void onRequestPermissionsResult(int requestCode, @NonNull String[] permissions, @NonNull int[] grantResults) {
        super.onRequestPermissionsResult(requestCode, permissions, grantResults);
        permissionHelper.onRequestCallBack(requestCode, permissions, grantResults);
    }

    @Override
    public void setContent(Profile profile) {
        nama_asesor.setText(profile.getFirstName() + " " + profile.getLastName());
        no_registrasi.setText(profile.getRegistrationNumber());
        nik.setText(profile.getNik());
        npwp.setText(profile.getNpwp());
        email_asesor.setText(profile.getEmail());
        alamat_asesor.setText(profile.getAddress());
        tlp_asesor.setText(profile.getContact());
        tempat_lahir_asesor.setText(profile.getPlaceOfBirth());
        try {
            editTglLahir.setText(MyUtils.dateFormatter("yyyy-MM-dd", profile.getDateOfBirth(), "dd MMMM yyyy"));
        } catch (ParseException e) {
            e.printStackTrace();
        }

        if (profile.getGenderCode().equals("M"))
            radioGroupGender.check(R.id.gender_male);
        else if (profile.getGenderCode().equals("F"))
            radioGroupGender.check(R.id.gender_female);

        if (LSPUtils.getRoleCode().equals(RoleEntity.MANAGEMENT) && profile.getLevelManagement().equals("2")) {
            signature.setVisibility(View.GONE);
            signatureStatus.setVisibility(View.GONE);
        }

        MyUtils.getImageWithGlide(this, profile.getPicture(), imgEditProfile);

        if (profile.getNpwpPhoto() == null) {
            uploadNPWP.setText(R.string.upload_npwp);
            uploadNPWP.setOnClickListener(view -> checkAndRequestPermissions("upload_npwp"));
        } else {
            uploadNPWP.setText(R.string.show_npwp);
            uploadNPWP.setOnClickListener(view -> MyUtils.showImagePopupDialog(view.getContext(), profile.getNpwpPhoto()));
        }

        signatureFlag = profile.getSignatureFlag();
        if (encodedSignature == null && signatureFlag == 0) {
            signatureStatus.setText(R.string.signature_uncompleted);
            signatureStatus.setVisibility(View.VISIBLE);
        } else {
            signatureStatus.setText(R.string.signature_completed);
            signatureStatus.setVisibility(View.VISIBLE);
            signature.setEnabled(false);
        }

//        integrityFlag = profile.getIntegrityFlag();
//        if (integrityFlag == 0)
//            integrityStatus.setText(R.string.pakta_integritas_undone);
//        else
//            integrityStatus.setText(R.string.pakta_integritas_done);

        sertifikatFlag = profile.getSertifikatBnsp();
        if (sertifikatFlag == null) {
            sertifikatBnsp.setText(R.string.upload_assessor_certificate);
            sertifikatBnsp.setOnClickListener(view -> checkAndRequestPermissions("sertifikat_bnsp"));
        } else {
            sertifikatBnsp.setText(R.string.show_assessor_certificate);
            sertifikatBnsp.setOnClickListener(view -> MyUtils.showImagePopupDialog(view.getContext(), profile.getSertifikatBnsp()));
        }
    }

    @Override
    public void showToast(String message) {
        Toast.makeText(this, message, Toast.LENGTH_SHORT).show();
    }

    @Override
    public void setImageBase64(Bitmap bitmap) {
        imgEditProfile.setImageBitmap(bitmap);
        encodeBase64 = MyUtils.convertBitmapToBase64(bitmap);
        Profile profile = new Profile();
        profile.setImageBase64(encodeBase64);
        presenter.saveImgProfile(profile);
    }

    @Override
    public void setNpwpBase64(Bitmap bitmap) {
        npwpBase64 = MyUtils.convertBitmapToBase64(bitmap);
        readyToUplaodNpwp.setVisibility(View.VISIBLE);
        uploadNPWP.setVisibility(View.GONE);
    }

    @Override
    public void setSertifikatBase64(FileData fileData) {
        if (fileData.getFilename() != null) {
            sertifikatFileName.setVisibility(View.VISIBLE);
            sertifikatFileName.setText(String.valueOf(fileData.getFilename()));
        }
        Bitmap bitmap = MyUtils.convertToBitmap(fileData);
        sertifikatBase64 = MyUtils.convertBitmapToBase64(bitmap);
    }

    @Override
    public void finishActivity() {
        finish();
    }
}
