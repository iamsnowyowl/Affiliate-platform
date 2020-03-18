package com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.Profile.Sertifikasi.AddSertifikasi;

import android.Manifest;
import android.annotation.SuppressLint;
import android.content.Intent;
import android.graphics.Bitmap;
import android.os.Bundle;
import android.support.design.widget.Snackbar;
import android.support.v7.app.AlertDialog;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.Spinner;
import android.widget.TextView;
import android.widget.Toast;

import com.karumi.dexter.MultiplePermissionsReport;
import com.karumi.dexter.PermissionToken;
import com.karumi.dexter.listener.PermissionRequest;
import com.karumi.dexter.listener.multi.MultiplePermissionsListener;

import org.greenrobot.eventbus.Subscribe;

import java.text.SimpleDateFormat;
import java.util.List;
import java.util.Locale;

import butterknife.BindView;
import butterknife.OnClick;

import com.aplikasisertifikasi.asesor.lspabi.MVPCore.BaseActivity;
import com.aplikasisertifikasi.asesor.lspabi.Model.AccessorCompetence;
import com.aplikasisertifikasi.asesor.lspabi.Model.SubschemeCompetency;
import com.aplikasisertifikasi.asesor.lspabi.Model.SchemeCompetency;
import com.aplikasisertifikasi.asesor.lspabi.Model.ResponseMessage;
import com.aplikasisertifikasi.asesor.lspabi.R;
import com.aplikasisertifikasi.asesor.lspabi.Utils.DexterUtils;
import com.aplikasisertifikasi.asesor.lspabi.Utils.MyUtils;
import com.aplikasisertifikasi.asesor.lspabi.Utils.ProgressLoadingBar;

public class AddSertifikasi extends BaseActivity implements AddSertifikasiContract.View {

    @BindView(R.id.layout)
    LinearLayout layout;
    @BindView(R.id.spinner_faculty)
    Spinner spinnerFaculty;
    @BindView(R.id.spinner_department)
    Spinner spinnerDepartment;
    //    @BindView(R.id.expired_date)
//    TextView expiredDate;
    @BindView(R.id.img_sertifikat)
    ImageView imgSertifikat;
    @BindView(R.id.tv_add_sertifikat)
    TextView txtAddSertifikat;
    @BindView(R.id.upload_data_sertifikat)
    Button uploadCertificate;
    @BindView(R.id.btn_add_sertifikat)
    Button addCertificate;

    AddSertifikasiPresenter presenter = new AddSertifikasiPresenter(this);
    int currentSchema, currentSubscheme;
    List<SchemeCompetency> schemeCompetencies;
    List<SubschemeCompetency> subschemeCompetencies;
    SimpleDateFormat dateFormatter, sendingFormat;

    String encodeBase64 = "";
    String schemaId, subschemaNumber, expiredDatePicked;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        presenter.start();
        dateFormatter = new SimpleDateFormat("dd-MM-yyyy", Locale.US);
        sendingFormat = new SimpleDateFormat("yyyy-MM-dd");
    }

    @Override
    protected int getLayoutId() {
        return R.layout.activity_add_sertifikasi;
    }

    @Subscribe
    public void onResponseEvent(ResponseMessage responseMessage) {
    }

    @SuppressLint("ResourceAsColor")
    @Override
    protected void onResume() {
        super.onResume();
        if (encodeBase64.equals("")) {
            uploadCertificate.setVisibility(View.INVISIBLE);
            addCertificate.setVisibility(View.VISIBLE);
            txtAddSertifikat.setText(R.string.photo_certificate);
            txtAddSertifikat.setTextColor(this.getResources().getColor(R.color.secondaryColor));
        } else {
            uploadCertificate.setVisibility(View.VISIBLE);
            addCertificate.setVisibility(View.GONE);
            txtAddSertifikat.setText(R.string.add_photo_certificate);
            txtAddSertifikat.setTextColor(this.getResources().getColor(R.color.primaryColorDark));
        }
    }

//    @OnClick(R.id.expired_date)
//    public void pickExpiredDate() {
//        Calendar calendar = Calendar.getInstance();
//        DatePickerDialog datePickerDialog = new DatePickerDialog(this, (view, year, month, dayOfMonth) -> {
//            Calendar newDate = Calendar.getInstance();
//            newDate.set(year, month, dayOfMonth);
//            expiredDate.setText(dateFormatter.format(newDate.getTime()));
//            expiredDatePicked = sendingFormat.format(newDate.getTime());
//        }, calendar.get(Calendar.YEAR), calendar.get(Calendar.MONTH), calendar.get(Calendar.DAY_OF_MONTH));
//        datePickerDialog.show();
//    }

    @OnClick(R.id.tv_add_sertifikat)
    public void onAddCertificate() {
        selectImage();
    }

    @OnClick(R.id.btn_close_add_certificate)
    public void onCloseButtonPressed() {
        finish();
    }

    @OnClick(R.id.btn_add_sertifikat)
    public void chooseCertificate() {
        selectImage();
    }

    @OnClick(R.id.upload_data_sertifikat)
    public void uplaodDataCertificate() {
        if (encodeBase64 == null || subschemaNumber == null) {
            Snackbar.make(layout, R.string.data_must_completed, Snackbar.LENGTH_SHORT).show();
        } else {
            AccessorCompetence accessorCompetence = new AccessorCompetence();
            accessorCompetence.setImageBase64(encodeBase64);
            accessorCompetence.setSubSchemaNumber(subschemaNumber);
//            accessorCompetence.setExpiredDate(expiredDatePicked);
            presenter.uploadCertificate(accessorCompetence);
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

    @Override
    public void startActivity(Class c) {
        startActivity(new Intent(this, c));
        finish();
    }

    @Override
    public void initViews() {
        presenter.getSchemeCompetencies();
    }

    @Override
    public void setFacultyAdapter(List<String> stringList, List<SchemeCompetency> schemeCompetencyList) {
        schemeCompetencies = schemeCompetencyList;
        ArrayAdapter<String> adapter = new ArrayAdapter<String>(this, R.layout.spinner_item, R.id.spinner_text, stringList);
        adapter.setDropDownViewResource(R.layout.spinner_item);
        spinnerFaculty.setAdapter(adapter);
        spinnerFaculty.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
            @Override
            public void onItemSelected(AdapterView<?> parent, View view, int position, long id) {
                currentSchema = position - 1;
                if (position > 0) {
                    presenter.getSubschemeCompetency(schemeCompetencies.get(currentSchema).getSchemaId());
                    schemaId = schemeCompetencies.get(currentSchema).getSchemaId();
                }
            }

            @Override
            public void onNothingSelected(AdapterView<?> parent) {

            }
        });
    }

    @Override
    public void setDepartmentFromFacultyAdapter(List<String> stringList, List<SubschemeCompetency> subschemeCompetencyList) {
        subschemeCompetencies = subschemeCompetencyList;
        ArrayAdapter<String> adapter = new ArrayAdapter<String>(this, R.layout.spinner_item, R.id.spinner_text, stringList);
        adapter.setDropDownViewResource(R.layout.spinner_item);
        spinnerDepartment.setAdapter(adapter);
        spinnerDepartment.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
            @Override
            public void onItemSelected(AdapterView<?> parent, View view, int position, long id) {
                currentSubscheme = position - 1;
                if (position > 0)
                    subschemaNumber = subschemeCompetencies.get(currentSubscheme).getSubSchemaNumber();
            }

            @Override
            public void onNothingSelected(AdapterView<?> parent) {

            }
        });
    }

    @Override
    public void showToast(String message) {
        Toast.makeText(this, message, Toast.LENGTH_SHORT).show();
    }

    @Override
    public void onUploadComplete() {
        onBackPressed();
        finish();
    }

    @Override
    public void setImageBase64(Bitmap bitmap) {
        imgSertifikat.setImageBitmap(bitmap);
        encodeBase64 = MyUtils.convertBitmapToBase64(bitmap);
    }

    @Override
    public void showSendButton() {
        if (encodeBase64.equals("")) {
            uploadCertificate.setVisibility(View.INVISIBLE);
            addCertificate.setVisibility(View.VISIBLE);
            txtAddSertifikat.setText(R.string.photo_certificate);
            txtAddSertifikat.setTextColor(this.getResources().getColor(R.color.secondaryColor));
        } else {
            uploadCertificate.setVisibility(View.VISIBLE);
            addCertificate.setVisibility(View.GONE);
            txtAddSertifikat.setText(R.string.add_photo_certificate);
            txtAddSertifikat.setTextColor(this.getResources().getColor(R.color.primaryColorDark));
        }
    }

    private void selectImage() {
        DexterUtils.setPermissions(this, new MultiplePermissionsListener() {
            @Override
            public void onPermissionsChecked(MultiplePermissionsReport report) {
                final CharSequence[] items = {getString(R.string.take_photo), getString(R.string.choose_photo),
                        getString(R.string.cancel)};
                AlertDialog.Builder builder = new AlertDialog.Builder(AddSertifikasi.this);
                builder.setTitle(R.string.add_photo);
                builder.setIcon(R.drawable.camera);
                builder.setItems(items, (dialog, item) -> {
                    if (items[item].equals(getString(R.string.take_photo))) {
                        openCamera();
                    } else if (items[item].equals(getString(R.string.choose_photo))) {
                        openGalery();
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

    private void openGalery() {
        presenter.takePictFromGalery(this);
    }

    private void openCamera() {
        presenter.takePictFromCamera(this);
    }

}
