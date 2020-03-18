package com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.Home.LokasiUjian;

import android.os.Bundle;
import android.widget.TextView;

import butterknife.BindView;

import com.aplikasisertifikasi.asesor.lspabi.Entity.AssessmentEntity;
import com.aplikasisertifikasi.asesor.lspabi.MVPCore.BaseActivity;
import com.aplikasisertifikasi.asesor.lspabi.R;
import com.aplikasisertifikasi.asesor.lspabi.Utils.MyUtils;

import java.text.ParseException;

public class LokasiUjian extends BaseActivity implements LokasiUjianContract.View {
    @BindView(R.id.assessment_title)
    TextView assessmentTitle;
    @BindView(R.id.assessment_address)
    TextView assessmentLocation;
    @BindView(R.id.tuk_name)
    TextView tukName;
    @BindView(R.id.assessment_note)
    TextView assessmentNote;
    @BindView(R.id.assessment_start_date)
    TextView assessmentStartdate;

    String latitude, longitude;
    LokasiUjianPresenter presenter = new LokasiUjianPresenter(this);

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        latitude = getIntent().getStringExtra(AssessmentEntity.LAT_ASSESSMENT);
        longitude = getIntent().getStringExtra(AssessmentEntity.LONG_ASSESSMENT);
        assessmentTitle.setText(getIntent().getStringExtra(AssessmentEntity.TITLE_ASSESSMENT));
        assessmentLocation.setText(getIntent().getStringExtra(AssessmentEntity.ADDRESS_ASSESSMENT));
        tukName.setText(getIntent().getStringExtra(AssessmentEntity.TUK_ASSESSMENT));
        assessmentNote.setText(getIntent().getStringExtra(AssessmentEntity.NOTE_ASSESSMENT));
        try {
            assessmentStartdate.setText(MyUtils.dateFormatter("yyyy-MM-dd hh:mm:ss", getIntent().getStringExtra(AssessmentEntity.STARTDATE_ASSESSMENT), "dd MMMM yyyy"));
        } catch (ParseException e) {
            e.printStackTrace();
        }
    }

    @Override
    protected int getLayoutId() {
        return R.layout.activity_lokasi_ujian;
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
    public void startActivity(Class c) {

    }

    @Override
    public void initViews() {
    }
}
