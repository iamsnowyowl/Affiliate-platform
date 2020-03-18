package com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.Home.DetailHome;

import android.content.Intent;
import android.support.v4.app.Fragment;
import android.os.Bundle;
import android.widget.FrameLayout;
import android.widget.TextView;

import com.aplikasisertifikasi.asesor.lspabi.Entity.AssessmentEntity;
import com.aplikasisertifikasi.asesor.lspabi.Entity.StatusKegiatanEntity;
import com.aplikasisertifikasi.asesor.lspabi.Listener.FragmentInteractionListener;
import com.aplikasisertifikasi.asesor.lspabi.MVPCore.BaseActivity;
import com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.Home.DetailHome.Penilaian.PenilaianFragment;
import com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.Home.DetailHome.PreAsesmen.PreAsesmenFragment;
import com.aplikasisertifikasi.asesor.lspabi.R;
import com.aplikasisertifikasi.asesor.lspabi.Utils.MyUtils;

import butterknife.BindView;

public class DetailHome extends BaseActivity implements DetailHomeContract.View, FragmentInteractionListener {

    @BindView(R.id.assessmen_step)
    TextView assessmenStep;
    @BindView(R.id.pra_assessment_title)
    TextView assessmenTitle;
    @BindView(R.id.pra_assessment_note)
    TextView assessmenNote;
    @BindView(R.id.step_frame)
    FrameLayout stepframe;
    private PreAsesmenFragment preAsesmen;
    private PenilaianFragment penilaian;
    DetailHomePresenter presenter = new DetailHomePresenter(this);


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        presenter.start();

        Bundle bundle = new Bundle();
        bundle.putString(AssessmentEntity.ASSESSMENT_ID, getIntent().getStringExtra(AssessmentEntity.ASSESSMENT_ID));

        assessmenTitle.setText(getIntent().getStringExtra(AssessmentEntity.TITLE_ASSESSMENT));
        assessmenNote.setText(getIntent().getStringExtra(AssessmentEntity.NOTE_ASSESSMENT));

        preAsesmen.setArguments(bundle);
        penilaian.setArguments(bundle);
    }

    @Override
    protected int getLayoutId() {
        return R.layout.activity_detail_home;
    }

    @Override
    public void onBackPressed() {
        finish();
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
    public void determineFragment() {
        changeFragment(preAsesmen);

        String status = getIntent().getStringExtra(AssessmentEntity.STATUS_ASSESSMENT);

        if (status.equals(StatusKegiatanEntity.ON_REVIEW_APPLICANT_DOCUMENT)) {
            assessmenStep.setText(R.string.pra_assessment);
            changeFragment(preAsesmen);
        } else {
            assessmenStep.setText(R.string.assement);
            changeFragment(penilaian);
        }
    }

    @Override
    public void changeFragment(Fragment fragment) {
        MyUtils.changeStepFragment(
                R.id.step_frame,
                getSupportFragmentManager(),
                fragment
        );
    }

    @Override
    public void initFragmentListener() {
        preAsesmen = PreAsesmenFragment.getInstance();
        penilaian = PenilaianFragment.getInstance();

        preAsesmen.setFragmentInteractionListener(this);
    }

    @Override
    public void startActivity(Class c) {
        startActivity(new Intent(this, c));
    }

    @Override
    public void initViews() {

    }

    @Override
    public void onFragmentInteraction(Fragment fragment) {
        MyUtils.changeStepFragment(
                R.id.step_frame,
                getSupportFragmentManager(),
                fragment
        );
    }

    @Override
    public void closeActivity() {
        finish();
    }
}
