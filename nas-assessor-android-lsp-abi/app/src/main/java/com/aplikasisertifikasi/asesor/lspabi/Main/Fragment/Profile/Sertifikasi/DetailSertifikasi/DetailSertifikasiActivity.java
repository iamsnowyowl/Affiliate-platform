package com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.Profile.Sertifikasi.DetailSertifikasi;

import android.annotation.SuppressLint;
import android.content.Intent;
import android.os.Bundle;
import android.widget.ImageView;
import android.widget.TextView;

import butterknife.BindView;
import butterknife.OnClick;

import com.aplikasisertifikasi.asesor.lspabi.Entity.CompetenceEntity;
import com.aplikasisertifikasi.asesor.lspabi.MVPCore.BaseActivity;
import com.aplikasisertifikasi.asesor.lspabi.Model.AccessorCompetence;
import com.aplikasisertifikasi.asesor.lspabi.R;
import com.aplikasisertifikasi.asesor.lspabi.Utils.MyUtils;
import com.aplikasisertifikasi.asesor.lspabi.Utils.ProgressLoadingBar;

public class DetailSertifikasiActivity extends BaseActivity implements DetailSertifikasiContract.View {
    @BindView(R.id.detail_competence_field)
    TextView competenceFieldName;
    @BindView(R.id.detail_expired_date)
    TextView expiredDate;
    @BindView(R.id.detail_verification_status)
    TextView verificationStatus;
    @BindView(R.id.detail_verification_date)
    TextView verficationDate;
    @BindView(R.id.img_detail_certificate)
    ImageView imgDetailCertificate;
    DetailSertifikasiPresenter presenter = new DetailSertifikasiPresenter(this);
    String id_accessor_skill;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        presenter.start();
        id_accessor_skill = getIntent().getExtras().getString(CompetenceEntity.ACCESSOR_COMPETENCE_ID);
    }

    @Override
    protected void onResume() {
        super.onResume();
        presenter.getDetailSkill(id_accessor_skill);
    }

    @Override
    protected int getLayoutId() {
        return R.layout.activity_detail_sertifikasi;
    }

    @OnClick(R.id.btn_close_detail_certificate)
    public void onClosePressed() {
        onBackPressed();
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
    public void startActivity(Class<?> c) {
        startActivity(new Intent(this, c));
    }

    @Override
    public void initViews() {

    }

    @SuppressLint("ResourceAsColor")
    @Override
    public void setDetailSkill(AccessorCompetence accessorCompetence) {
        MyUtils.getImageWithGlide(this, accessorCompetence.getCertificateFile(), imgDetailCertificate);
        imgDetailCertificate.setOnClickListener(view -> {
            MyUtils.showImagePopupDialog(this, accessorCompetence.getCertificateFile());
        });

        competenceFieldName.setText(accessorCompetence.getSubSchemaName());
        expiredDate.setText(accessorCompetence.getExpiredDate());
        if (accessorCompetence.getVerificationFlag() == 1) {
            verificationStatus.setText(R.string.confirmed);
            verficationDate.setText(accessorCompetence.getVerificationDate());
            expiredDate.setText(accessorCompetence.getExpiredDate());
        } else {
            verificationStatus.setText(R.string.waiting_confirmation);
            verficationDate.setText(R.string.waiting_confirmation);
            expiredDate.setText(R.string.waiting_confirmation);
        }
        if (accessorCompetence.getExpiredFlag() == 1) {
            expiredDate.setTextColor(this.getResources().getColor(R.color.md_red_300));
            expiredDate.setText(R.string.certificate_expired);
        }
    }
}
