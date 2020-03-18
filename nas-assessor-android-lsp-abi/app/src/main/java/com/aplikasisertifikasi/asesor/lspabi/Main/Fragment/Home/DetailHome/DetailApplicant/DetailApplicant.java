package com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.Home.DetailHome.DetailApplicant;

import android.app.DownloadManager;
import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.Intent;
import android.content.IntentFilter;
import android.os.Bundle;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.LinearLayout;
import android.widget.TextView;
import android.widget.Toast;

import java.util.List;

import butterknife.BindView;
import de.hdodenhof.circleimageview.CircleImageView;

import com.aplikasisertifikasi.asesor.lspabi.Adapter.PersyaratanUmumAdapter;
import com.aplikasisertifikasi.asesor.lspabi.Adapter.PersyaratanUmumNonAccountAdapter;
import com.aplikasisertifikasi.asesor.lspabi.Adapter.PortofolioAdapter;
import com.aplikasisertifikasi.asesor.lspabi.Entity.ApplicantEntity;
import com.aplikasisertifikasi.asesor.lspabi.Entity.AssessmentEntity;
import com.aplikasisertifikasi.asesor.lspabi.Entity.StatusKegiatanEntity;
import com.aplikasisertifikasi.asesor.lspabi.MVPCore.BaseActivity;
import com.aplikasisertifikasi.asesor.lspabi.Model.Applicant;
import com.aplikasisertifikasi.asesor.lspabi.Model.Portofolio;
import com.aplikasisertifikasi.asesor.lspabi.R;
import com.aplikasisertifikasi.asesor.lspabi.Utils.MyUtils;
import com.aplikasisertifikasi.asesor.lspabi.Utils.ProgressLoadingBar;

public class DetailApplicant extends BaseActivity implements DetailApplicantContract.View {
    @BindView(R.id.preassessmen_applicant_img)
    CircleImageView applicantImage;
    @BindView(R.id.preassessmen_applicant_name)
    TextView applicantName;
    @BindView(R.id.preassessmen_competence)
    TextView applicantCompetence;
    @BindView(R.id.preassessmen_tuk)
    TextView applicantTuk;
    @BindView(R.id.preassessmen_contact)
    TextView applicantContact;
    @BindView(R.id.test_method)
    TextView tvTestMethod;
    @BindView(R.id.status_recommendation)
    TextView tvStatusRecommendation;
    @BindView(R.id.status_graduation)
    TextView tvStatusGraduation;
    @BindView(R.id.preassessmen_portofolio)
    RecyclerView portofolioRecycler;
    @BindView(R.id.persyaratan_umum)
    RecyclerView persyaratanUmumRecycler;
    @BindView(R.id.applicant_container)
    LinearLayout applicantContainer;
    @BindView(R.id.error_container)
    LinearLayout errorContainer;
    @BindView(R.id.try_again_button)
    Button tryAgainButton;
    @BindView(R.id.btnTestPortfolio)
    Button testPortfolioBtn;
    @BindView(R.id.btnTestKompetensi)
    Button testKompetensiBtn;
    @BindView(R.id.btn_reset_test_method)
    Button resetTestMethod;

    DetailApplicantPresenter presenter = new DetailApplicantPresenter(this);
    String assessmentId, applicantId, assessmentApplicantId;
    long downloadID;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        presenter.start();
        registerReceiver(onDownloadComplete, new IntentFilter(DownloadManager.ACTION_DOWNLOAD_COMPLETE));

        assessmentId = getIntent().getStringExtra(AssessmentEntity.ASSESSMENT_ID);
        applicantId = getIntent().getStringExtra(ApplicantEntity.APPLICANT_ID);
        assessmentApplicantId = getIntent().getStringExtra(ApplicantEntity.ASSESSMENT_APPLICANT_ID);
    }

    @Override
    protected void onResume() {
        super.onResume();
        presenter.getDetailApplicant(assessmentId, assessmentApplicantId);
        presenter.getApplicantPortofolio(assessmentId, assessmentApplicantId, "DASAR");

        if (!applicantId.equals("0"))
            presenter.getApplicantPeryaratan(applicantId);
        else
            presenter.getApplicantPortofolio(assessmentId, assessmentApplicantId, "UMUM");

        registerReceiver(onDownloadComplete, new IntentFilter(DownloadManager.ACTION_DOWNLOAD_COMPLETE));
    }

    @Override
    protected int getLayoutId() {
        return R.layout.activity_detail_pre_asesmen;
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
        errorContainer.setVisibility(View.VISIBLE);
        applicantContainer.setVisibility(View.INVISIBLE);
        tryAgainButton.setOnClickListener(v -> {
            errorContainer.setVisibility(View.GONE);
            applicantContainer.setVisibility(View.VISIBLE);
            presenter.getDetailApplicant(assessmentId, assessmentApplicantId);
            presenter.getApplicantPortofolio(assessmentId, assessmentApplicantId, "DASAR");

            if (!applicantId.equals("0"))
                presenter.getApplicantPeryaratan(applicantId);
            else
                presenter.getApplicantPortofolio(assessmentId, assessmentApplicantId, "UMUM");
        });
    }

    private BroadcastReceiver onDownloadComplete = new BroadcastReceiver() {
        @Override
        public void onReceive(Context context, Intent intent) {
            //Fetching the download id received with the broadcast
            long id = intent.getLongExtra(DownloadManager.EXTRA_DOWNLOAD_ID, -1);
            //Checking if the received broadcast is for our enqueued download by matching download id
            if (downloadID == id) {
                Toast.makeText(context, "Download Completed", Toast.LENGTH_SHORT).show();
            }
        }
    };

    @Override
    public void startActivity(Class<?> c) {

    }

    @Override
    public void initViews() {
        String statusAssessment = getIntent().getStringExtra(AssessmentEntity.STATUS_ASSESSMENT);

//        if (!statusAssessment.equals(StatusKegiatanEntity.ON_REVIEW_APPLICANT_DOCUMENT)) {
//            testPortfolioBtn.setVisibility(View.GONE);
//            testKompetensiBtn.setVisibility(View.GONE);
//        }

        testPortfolioBtn.setOnClickListener(v -> presenter.updateTestMethod("portfolio", assessmentId, assessmentApplicantId));
        testKompetensiBtn.setOnClickListener(v -> presenter.updateTestMethod("competency", assessmentId, assessmentApplicantId));
        resetTestMethod.setOnClickListener(v -> presenter.updateTestMethod("", assessmentId, assessmentApplicantId));

        portofolioRecycler.setLayoutManager(new LinearLayoutManager(this, LinearLayoutManager.VERTICAL, false));
        persyaratanUmumRecycler.setLayoutManager(new LinearLayoutManager(this, LinearLayoutManager.VERTICAL, false));
    }

    @Override
    protected void onDestroy() {
        super.onDestroy();
        unregisterReceiver(onDownloadComplete);
    }

    @Override
    public void setDetailApplicant(Applicant profile) {
        MyUtils.getImageWithGlide(this, profile.getPicture(), applicantImage);
        applicantImage.setOnClickListener(view -> MyUtils.showImagePopupDialog(this, profile.getPicture()));

        if (!profile.getApplicantId().equals("0"))
            applicantName.setText(profile.getFirstName() + " " + profile.getLastName());
        else {
            applicantImage.setVisibility(View.INVISIBLE);
            applicantName.setText(profile.getFullName());
        }

        applicantContact.setText(profile.getContact());
        applicantCompetence.setText(profile.getCompetenceFieldLable());
        applicantTuk.setText(profile.getTukName());

        if (profile.getTestMethod().equals("portfolio") || profile.getTestMethod().equals("competency")) {
            testPortfolioBtn.setVisibility(View.GONE);
            testKompetensiBtn.setVisibility(View.GONE);
            resetTestMethod.setVisibility(View.VISIBLE);
        } else {
            testPortfolioBtn.setVisibility(View.VISIBLE);
            testKompetensiBtn.setVisibility(View.VISIBLE);
            resetTestMethod.setVisibility(View.GONE);
        }

        if (profile.getStatusRecomendation().equals("K"))
            tvStatusRecommendation.setText(R.string.recommended);
        else if (profile.getStatusRecomendation().equals("BK"))
            tvStatusRecommendation.setText(R.string.not_recommended);
        else
            tvStatusRecommendation.setText("");

        if (profile.getStatusGraduation().equals("L"))
            tvStatusGraduation.setText(R.string.competent);
        else if (profile.getStatusGraduation().equals("TL"))
            tvStatusGraduation.setText(R.string.not_competent);
        else
            tvStatusGraduation.setText("");


        if (profile.getTestMethod() != null) {
            if (profile.getTestMethod().equals("portfolio")) {
                tvTestMethod.setText(R.string.test_portfolio);
                testPortfolioBtn.setVisibility(View.GONE);
                testKompetensiBtn.setVisibility(View.GONE);
            } else if (profile.getTestMethod().equals("competency")) {
                tvTestMethod.setText(R.string.test_competency);
                testPortfolioBtn.setVisibility(View.GONE);
                testKompetensiBtn.setVisibility(View.GONE);
            }
        } else {
            tvTestMethod.setText("-");
        }
    }

    @Override
    public void setApplicantPortofolio(List<Portofolio> portofolios) {
        PortofolioAdapter adapter = new PortofolioAdapter(this, this, portofolios, downloadID);
        portofolioRecycler.setAdapter(adapter);
    }

    @Override
    public void setApplicantPersyaratanUmum(List<Portofolio> portofolios) {
        PersyaratanUmumAdapter adapter = new PersyaratanUmumAdapter(this, this, portofolios, downloadID);
        PersyaratanUmumNonAccountAdapter nonAcctountAdapter = new PersyaratanUmumNonAccountAdapter(this, this, portofolios, downloadID);
        persyaratanUmumRecycler.setAdapter(applicantId.equals("0") ? nonAcctountAdapter : adapter);
    }

    @Override
    public void finishActivity() {
        finish();
    }
}
