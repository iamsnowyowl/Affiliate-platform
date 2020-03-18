package com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.Home.SuratMenyurat;

import android.content.Intent;
import android.os.Bundle;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.view.View;
import android.widget.Button;
import android.widget.LinearLayout;
import android.widget.TextView;

import java.util.List;

import butterknife.BindView;
import butterknife.OnClick;
import com.aplikasisertifikasi.asesor.lspabi.Adapter.SuratMenyuratAdapter;
import com.aplikasisertifikasi.asesor.lspabi.Entity.AssessmentEntity;
import com.aplikasisertifikasi.asesor.lspabi.MVPCore.BaseActivity;
import com.aplikasisertifikasi.asesor.lspabi.Model.AssessmentLetters;
import com.aplikasisertifikasi.asesor.lspabi.R;
import com.aplikasisertifikasi.asesor.lspabi.Utils.ProgressLoadingBar;

public class SuratMenyuratActivity extends BaseActivity implements SuratMenyuratContract.View {
    @BindView(R.id.error_container)
    LinearLayout error_layout;
    @BindView(R.id.try_again_button)
    Button tryAgain;
    @BindView(R.id.empty_container)
    LinearLayout empty_layout;
    @BindView(R.id.surat_menyurat_recycler_view)
    RecyclerView suratMenyuratRecyclerView;
    @BindView(R.id.txt_empty)
    TextView emptyText;

    SuratMenyuratPresenter presenter = new SuratMenyuratPresenter(this);

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        presenter.start();
    }

    @Override
    protected void onResume() {
        super.onResume();
        presenter.getListSuratMenyurat(getIntent().getStringExtra(AssessmentEntity.ASSESSMENT_ID));
    }

    @OnClick(R.id.btn_close_add_certificate)
    public void closePressed() {
        onBackPressed();
    }

    @Override
    protected int getLayoutId() {
        return R.layout.activity_surat_menyurat;
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
        error_layout.setVisibility(View.VISIBLE);
        suratMenyuratRecyclerView.setVisibility(View.GONE);
        empty_layout.setVisibility(View.GONE);
        tryAgain.setOnClickListener(view -> {
            presenter.getListSuratMenyurat(getIntent().getStringExtra(AssessmentEntity.ASSESSMENT_ID));
        });
    }

    @Override
    public void startActivity(Class<?> c) {
        startActivity(new Intent(this, c));
    }

    @Override
    public void initViews() {

    }

    @Override
    public void setSuratToAdapter(List<AssessmentLetters> assessmentLetters) {
        suratMenyuratRecyclerView.setLayoutManager(new LinearLayoutManager(this, LinearLayoutManager.VERTICAL, false));
        suratMenyuratRecyclerView.setAdapter(new SuratMenyuratAdapter(this, assessmentLetters));

        if (assessmentLetters.size() < 1) {
            suratMenyuratRecyclerView.setVisibility(View.GONE);
            empty_layout.setVisibility(View.VISIBLE);
            emptyText.setText(R.string.empty_letters);
        } else {
            suratMenyuratRecyclerView.setVisibility(View.VISIBLE);
            empty_layout.setVisibility(View.GONE);
        }

    }
}
