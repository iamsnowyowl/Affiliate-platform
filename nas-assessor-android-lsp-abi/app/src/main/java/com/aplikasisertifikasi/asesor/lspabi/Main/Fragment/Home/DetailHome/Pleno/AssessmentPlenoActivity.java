package com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.Home.DetailHome.Pleno;

import android.os.Bundle;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.view.View;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.ProgressBar;
import android.widget.TextView;
import android.widget.Toast;

import com.aplikasisertifikasi.asesor.lspabi.Adapter.ApplicantsAdapter;
import com.aplikasisertifikasi.asesor.lspabi.Entity.AssessmentEntity;
import com.aplikasisertifikasi.asesor.lspabi.Entity.PaginationEntity;
import com.aplikasisertifikasi.asesor.lspabi.MVPCore.BaseActivity;
import com.aplikasisertifikasi.asesor.lspabi.Model.Applicant;
import com.aplikasisertifikasi.asesor.lspabi.R;
import com.aplikasisertifikasi.asesor.lspabi.Utils.MyUtils;
import com.aplikasisertifikasi.asesor.lspabi.Utils.Pagination.PaginationRecyclerViewListener;
import com.aplikasisertifikasi.asesor.lspabi.Utils.ProgressLoadingBar;

import java.text.ParseException;
import java.util.List;

import butterknife.BindView;

public class AssessmentPlenoActivity extends BaseActivity implements AssessmentPlenoContract.View {
    @BindView(R.id.pleno_assessment_title)
    TextView assessmentTitle;
    @BindView(R.id.pleno_assessment_date)
    TextView assessmentDate;
    @BindView(R.id.pleno_recycler_view)
    RecyclerView recyclerView;
    @BindView(R.id.next_page_loading)
    ProgressBar loading;
    @BindView(R.id.empty_container)
    LinearLayout emptyContainer;
    @BindView(R.id.try_again_button)
    Button tryAgainButton;
    @BindView(R.id.error_container)
    LinearLayout errorContainer;
    @BindView(R.id.img_empty)
    ImageView imgEmpty;
    @BindView(R.id.txt_empty)
    TextView txtEmpty;

    LinearLayoutManager linearLayoutManager;
    AssessmentPlenoPresenter presenter = new AssessmentPlenoPresenter(this, this);
    ApplicantsAdapter adapter;
    String assessmentId;
    int currentApplicantPosition;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        presenter.start();
        assessmentId = getIntent().getStringExtra(AssessmentEntity.ASSESSMENT_ID);

        assessmentTitle.setText(getIntent().getStringExtra(AssessmentEntity.TITLE_ASSESSMENT));
        try {
            assessmentDate.setText(MyUtils.dateFormatter("yyyy-MM-dd hh:mm:ss", getIntent().getStringExtra(AssessmentEntity.STARTDATE_ASSESSMENT), "dd MMMM yyyy"));
        } catch (ParseException e) {
            e.printStackTrace();
        }
    }

    @Override
    protected void onResume() {
        super.onResume();
        presenter.getListApplicantPleno(PaginationEntity.LIMIT, PaginationEntity.OFFSET, assessmentId);
    }

    @Override
    protected int getLayoutId() {
        return R.layout.activity_assessment_pleno;
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
        recyclerView.setVisibility(View.GONE);
        emptyContainer.setVisibility(View.GONE);
        tryAgainButton.setOnClickListener(view -> presenter.getListApplicantPleno(PaginationEntity.LIMIT, PaginationEntity.OFFSET, assessmentId));
    }

    @Override
    public void startActivity(Class<?> c) {

    }

    @Override
    public void initViews() {
        linearLayoutManager = new LinearLayoutManager(this, LinearLayoutManager.VERTICAL, false);
        recyclerView.setLayoutManager(linearLayoutManager);
    }

    @Override
    public void setListApplicantPleno(List<Applicant> applicants) {
        adapter = new ApplicantsAdapter(this, applicants, assessmentId, getIntent().getStringExtra(AssessmentEntity.USER_PLENO), ApplicantsAdapter.AssessmentStep.PLENO);
        recyclerView.setAdapter(adapter);

        adapter.setRecyclerViewListener((view, applicant, position) -> {
            currentApplicantPosition = position;
            presenter.updateGraduateStatus(applicant.getStatusGraduation(), assessmentId, applicant.getAssessmentApplicantId());
        });

        if (applicants.size() < 1) {
            recyclerView.setVisibility(View.GONE);
            emptyContainer.setVisibility(View.VISIBLE);
            imgEmpty.setImageResource(R.drawable.empty);
            txtEmpty.setText(R.string.empty_assessee);
        } else {
            recyclerView.setVisibility(View.VISIBLE);
            emptyContainer.setVisibility(View.GONE);
        }
    }

    @Override
    public void showToast(String message) {
        Toast.makeText(this, message, Toast.LENGTH_SHORT).show();
    }

    @Override
    public void setNextPage(List<Applicant> applicants) {
        adapter.addApplicants(applicants);
    }

    @Override
    public void pagination() {
        recyclerView.addOnScrollListener(new PaginationRecyclerViewListener(linearLayoutManager, PaginationEntity.LIMIT) {
            @Override
            protected void loadMoreItems(int currentOffset) {
                presenter.loadNextPage(PaginationEntity.LIMIT, currentOffset, assessmentId);
            }
        });
    }

    @Override
    public void showLoadProgress() {
        loading.setVisibility(View.VISIBLE);
    }

    @Override
    public void dismissLoadProgress() {
        loading.setVisibility(View.GONE);
    }

    @Override
    public void updateStatusGraduation(String status) {
        adapter.getApplicantPosition(currentApplicantPosition).setStatusGraduation(status);

        adapter.notifyDataSetChanged();
    }
}
