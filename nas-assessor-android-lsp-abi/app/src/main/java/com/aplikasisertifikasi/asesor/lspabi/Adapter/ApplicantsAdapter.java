package com.aplikasisertifikasi.asesor.lspabi.Adapter;

import android.content.Context;
import android.content.Intent;
import android.support.v4.content.ContextCompat;
import android.support.v7.widget.CardView;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.aplikasisertifikasi.asesor.lspabi.Entity.ApplicantEntity;
import com.aplikasisertifikasi.asesor.lspabi.Entity.AssessmentEntity;
import com.aplikasisertifikasi.asesor.lspabi.Entity.StatusKegiatanEntity;
import com.aplikasisertifikasi.asesor.lspabi.Listener.RecyclerViewListener;
import com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.Home.DetailHome.DetailApplicant.DetailApplicant;
import com.aplikasisertifikasi.asesor.lspabi.Model.Applicant;
import com.aplikasisertifikasi.asesor.lspabi.R;
import com.aplikasisertifikasi.asesor.lspabi.Utils.DialogWithDescription;

import java.util.List;

import butterknife.BindView;
import butterknife.ButterKnife;

public class ApplicantsAdapter extends RecyclerView.Adapter<RecyclerView.ViewHolder> {

    List<Applicant> applicants;
    RecyclerViewListener<Applicant> recyclerViewListener;
    Context context;
    AssessmentStep assessmentStep;
    String assessmentId;
    String isUserPleno;
    String status;

    public enum AssessmentStep {
        PRA_ASSESSMENT, REAL_ASSESSMENT, PLENO
    }

    public ApplicantsAdapter(Context context, List<Applicant> applicants, String assessmentId, AssessmentStep assessmentStep) {
        this.context = context;
        this.applicants = applicants;
        this.assessmentId = assessmentId;
        this.assessmentStep = assessmentStep;

    }

    public ApplicantsAdapter(Context context, List<Applicant> applicants, String assessmentId, String isUserPleno, AssessmentStep assessmentStep) {
        this.context = context;
        this.applicants = applicants;
        this.assessmentId = assessmentId;
        this.isUserPleno = isUserPleno;
        this.assessmentStep = assessmentStep;
    }

    public void setRecyclerViewListener(RecyclerViewListener<Applicant> recyclerViewListener) {
        this.recyclerViewListener = recyclerViewListener;
    }

    public interface ViewBinding {
        void bind(int index);
    }

    public Applicant getApplicantPosition(int position) {
        return applicants.get(position);
    }

    abstract class BaseViewHolder extends RecyclerView.ViewHolder implements ApplicantsAdapter.ViewBinding {

        public BaseViewHolder(View itemView) {
            super(itemView);
        }
    }

    @Override
    public RecyclerView.ViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        return dispatchViewHolder(parent);
    }

    @Override
    public void onBindViewHolder(RecyclerView.ViewHolder holder, int position) {
        dispatchViews(holder, position);
    }

    private void dispatchViews(RecyclerView.ViewHolder holder, int position) {
        ApplicantsAdapter.ViewBinding viewBinding = (ApplicantsAdapter.BaseViewHolder) holder;

        viewBinding.bind(position);
    }

    RecyclerView.ViewHolder dispatchViewHolder(ViewGroup parent) {
        if (assessmentStep == AssessmentStep.PRA_ASSESSMENT)
            return new ApplicantsAdapter.PraAssessmentHolder(LayoutInflater.from(parent.getContext())
                    .inflate(R.layout.row_applicant,
                            parent,
                            false)
            );
        else if (assessmentStep == AssessmentStep.REAL_ASSESSMENT)
            return new ApplicantsAdapter.RealAssessmentHolder(LayoutInflater.from(parent.getContext())
                    .inflate(R.layout.row_applicant,
                            parent,
                            false)
            );
        else if (assessmentStep == AssessmentStep.PLENO)
            return new ApplicantsAdapter.PlenoHolder(LayoutInflater.from(parent.getContext())
                    .inflate(R.layout.row_applicant,
                            parent,
                            false)
            );
        else
            throw new UnsupportedOperationException("Adapter type not spesified");
    }

    @Override
    public int getItemCount() {
        return applicants.size();
    }

    public class PraAssessmentHolder extends BaseViewHolder {
        @BindView(R.id.applicant_name)
        TextView namaPeserta;
        @BindView(R.id.applicant_competence)
        TextView kompetensiPeserta;
        @BindView(R.id.applicant_test_method)
        TextView testMethod;
        @BindView(R.id.layout)
        RelativeLayout container;
        @BindView(R.id.applicant_button_container)
        LinearLayout button_container;
        @BindView(R.id.positive_button)
        CardView positiveButton;
        @BindView(R.id.negative_button)
        CardView negativeButton;
        @BindView(R.id.edit_button)
        CardView editButton;
        @BindView(R.id.right_arrow)
        ImageView rightArrow;
        @BindView(R.id.left_line)
        LinearLayout leftLine;

        public PraAssessmentHolder(View view) {
            super(view);
            ButterKnife.bind(this, view);
        }

        @Override
        public void bind(int index) {
            Applicant applicant = applicants.get(index);

            if (applicant.getApplicantId().equals("0"))
                namaPeserta.setText(applicant.getFullName());
            else
                namaPeserta.setText(applicant.getFirstName() + " " + applicant.getLastName());

            kompetensiPeserta.setText(applicant.getCompetenceFieldLable());

            leftLine.setBackgroundColor(ContextCompat.getColor(container.getContext(), R.color.lineGrey));
            if (applicant.getTestMethod() != null) {
                if (applicant.getTestMethod().equals("portfolio")) {
                    testMethod.setText(context.getString(R.string.test_status)+" "+context.getString(R.string.test_portfolio));
                    leftLine.setBackgroundColor(ContextCompat.getColor(container.getContext(), R.color.lineBlue));
                } else if (applicant.getTestMethod().equals("competency")) {
                    testMethod.setText(context.getString(R.string.test_status)+" "+context.getString(R.string.test_competency));
                    leftLine.setBackgroundColor(ContextCompat.getColor(container.getContext(), R.color.lineOrange));
                } else {
                    testMethod.setText(context.getString(R.string.test_status)+" -");
                }
            } else {
                testMethod.setText(context.getString(R.string.test_status)+" -");
            }

            container.setOnClickListener(v -> {
                Intent i = new Intent(v.getContext(), DetailApplicant.class);
                i.putExtra(ApplicantEntity.ASSESSMENT_APPLICANT_ID, applicant.getAssessmentApplicantId());
                i.putExtra(ApplicantEntity.APPLICANT_ID, applicant.getApplicantId());
                i.putExtra(AssessmentEntity.ASSESSMENT_ID, assessmentId);
                i.putExtra(AssessmentEntity.TEST_METHOD, applicant.getTestMethod());
                i.putExtra(AssessmentEntity.STATUS_ASSESSMENT, StatusKegiatanEntity.ON_REVIEW_APPLICANT_DOCUMENT);
                v.getContext().startActivity(i);
            });

//            container.setBackgroundColor(ContextCompat.getColor(container.getContext(), R.color.md_grey_100));
//            namaPeserta.setTextColor(ContextCompat.getColor(namaPeserta.getContext(), R.color.primaryTextColor));
//            kompetensiPeserta.setTextColor(ContextCompat.getColor(kompetensiPeserta.getContext(), R.color.secondaryTextColor));
        }
    }

    public class RealAssessmentHolder extends BaseViewHolder {
        @BindView(R.id.applicant_name)
        TextView namaPeserta;
        @BindView(R.id.applicant_competence)
        TextView kompetensiPeserta;
        @BindView(R.id.applicant_test_method)
        TextView testMethod;
        @BindView(R.id.layout)
        RelativeLayout container;
        @BindView(R.id.applicant_button_container)
        LinearLayout button_container;
        @BindView(R.id.positive_button)
        CardView positiveButton;
        @BindView(R.id.negative_button)
        CardView negativeButton;
        @BindView(R.id.edit_button)
        CardView editButton;
        @BindView(R.id.right_arrow)
        ImageView rightArrow;
        @BindView(R.id.left_line)
        LinearLayout leftLine;

        public RealAssessmentHolder(View view) {
            super(view);
            ButterKnife.bind(this, view);
        }

        @Override
        public void bind(int index) {
            Applicant applicant = applicants.get(index);

            rightArrow.setVisibility(View.GONE);
            button_container.setVisibility(View.VISIBLE);

            if (applicant.getApplicantId().equals("0"))
                namaPeserta.setText(applicant.getFullName());
            else
                namaPeserta.setText(applicant.getFirstName() + " " + applicant.getLastName());

            kompetensiPeserta.setText(applicant.getCompetenceFieldLable());

            leftLine.setBackgroundColor(ContextCompat.getColor(container.getContext(), R.color.lineGrey));
            if (applicant.getTestMethod() != null) {
                if (applicant.getTestMethod().equals("portfolio")) {
                    testMethod.setText(context.getString(R.string.test_status)+" "+context.getString(R.string.test_portfolio));
                    leftLine.setBackgroundColor(ContextCompat.getColor(container.getContext(), R.color.lineBlue));
                } else if (applicant.getTestMethod().equals("competency")) {
                    testMethod.setText(context.getString(R.string.test_status)+" "+context.getString(R.string.test_competency));
                    leftLine.setBackgroundColor(ContextCompat.getColor(container.getContext(), R.color.lineOrange));
                } else {
                    testMethod.setText(context.getString(R.string.test_status)+" -");
                }
            } else {
                testMethod.setText(context.getString(R.string.test_status)+" -");
            }

            itemView.setOnClickListener(v -> {
                Intent i = new Intent(v.getContext(), DetailApplicant.class);
                i.putExtra(ApplicantEntity.ASSESSMENT_APPLICANT_ID, applicant.getAssessmentApplicantId());
                i.putExtra(ApplicantEntity.APPLICANT_ID, applicant.getApplicantId());
                i.putExtra(AssessmentEntity.ASSESSMENT_ID, assessmentId);
                i.putExtra(AssessmentEntity.STATUS_ASSESSMENT, StatusKegiatanEntity.REAL_ASSESSMENT);
                v.getContext().startActivity(i);
            });

            if (applicant.getStatusRecomendation().equals("K")) {
                container.setBackgroundColor(ContextCompat.getColor(container.getContext(), R.color.md_green_300));
                namaPeserta.setTextColor(ContextCompat.getColor(namaPeserta.getContext(), R.color.white));
                kompetensiPeserta.setTextColor(ContextCompat.getColor(kompetensiPeserta.getContext(), R.color.white));
                testMethod.setTextColor(ContextCompat.getColor(kompetensiPeserta.getContext(), R.color.white));
                positiveButton.setVisibility(View.GONE);
                negativeButton.setVisibility(View.GONE);
                editButton.setVisibility(View.VISIBLE);
            } else if (applicant.getStatusRecomendation().equals("BK")) {
                container.setBackgroundColor(ContextCompat.getColor(container.getContext(), R.color.md_red_300));
                namaPeserta.setTextColor(ContextCompat.getColor(namaPeserta.getContext(), R.color.white));
                kompetensiPeserta.setTextColor(ContextCompat.getColor(kompetensiPeserta.getContext(), R.color.white));
                testMethod.setTextColor(ContextCompat.getColor(kompetensiPeserta.getContext(), R.color.white));
                positiveButton.setVisibility(View.GONE);
                negativeButton.setVisibility(View.GONE);
                editButton.setVisibility(View.VISIBLE);
            } else {
                container.setBackgroundColor(ContextCompat.getColor(container.getContext(), R.color.md_grey_100));
                namaPeserta.setTextColor(ContextCompat.getColor(namaPeserta.getContext(), R.color.primaryTextColor));
                kompetensiPeserta.setTextColor(ContextCompat.getColor(kompetensiPeserta.getContext(), R.color.secondaryTextColor));
                testMethod.setTextColor(ContextCompat.getColor(kompetensiPeserta.getContext(), R.color.secondaryTextColor));
                positiveButton.setVisibility(View.VISIBLE);
                negativeButton.setVisibility(View.VISIBLE);
                editButton.setVisibility(View.GONE);
            }

            positiveButton.setOnClickListener(v -> {
                DialogWithDescription dialog = new DialogWithDescription(v.getContext());
                dialog.setDialogTitle(context.getString(R.string.desc_if_recomended));
                dialog.setDialogDescriptionHint(context.getString(R.string.desc_placeholder));
                dialog.setPositiveButtonText(context.getString(R.string.ok));
                dialog.setNegativeButtonText(context.getString(R.string.cancel));
                dialog.positiveButton(view -> {
                    applicant.setStatusRecomendation("K");
                    applicant.setDescriptionRecomendation(dialog.getDialogDescription());
                    recyclerViewListener.onItemChooseCallback(view, applicant, index);
                    dialog.dismiss();
                });
                dialog.negativeButton(view -> dialog.dismiss());
                dialog.showDialog();
            });
            negativeButton.setOnClickListener(v -> {
                DialogWithDescription dialog = new DialogWithDescription(v.getContext());
                dialog.setDialogTitle(context.getString(R.string.desc_if_not_recomended));
                dialog.setDialogDescriptionHint(context.getString(R.string.desc_placeholder));
                dialog.setPositiveButtonText(context.getString(R.string.ok));
                dialog.setNegativeButtonText(context.getString(R.string.cancel));
                dialog.positiveButton(view -> {
                    applicant.setStatusRecomendation("BK");
                    applicant.setDescriptionRecomendation(dialog.getDialogDescription());
                    recyclerViewListener.onItemChooseCallback(view, applicant, index);
                    dialog.dismiss();
                });
                dialog.negativeButton(view -> dialog.dismiss());
                dialog.showDialog();
            });
            editButton.setOnClickListener(v -> {
                applicant.setStatusRecomendation("NONE");
                applicant.setDescriptionRecomendation("");
                recyclerViewListener.onItemChooseCallback(v, applicant, index);
            });
        }
    }

    public class PlenoHolder extends BaseViewHolder {
        @BindView(R.id.applicant_name)
        TextView namaPeserta;
        @BindView(R.id.applicant_competence)
        TextView kompetensiPeserta;
        @BindView(R.id.applicant_test_method)
        TextView testMethod;
        @BindView(R.id.layout)
        RelativeLayout container;
        @BindView(R.id.applicant_button_container)
        LinearLayout button_container;
        @BindView(R.id.positive_button)
        CardView positiveButton;
        @BindView(R.id.negative_button)
        CardView negativeButton;
        @BindView(R.id.edit_button)
        CardView editButton;
        @BindView(R.id.right_arrow)
        ImageView rightArrow;
        @BindView(R.id.left_line)
        LinearLayout leftLine;

        public PlenoHolder(View view) {
            super(view);
            ButterKnife.bind(this, view);
        }

        @Override
        public void bind(int index) {
            Applicant applicant = applicants.get(index);

            status = applicant.getStatusGraduation();
            rightArrow.setVisibility(View.GONE);
            button_container.setVisibility(View.VISIBLE);
            editButton.setVisibility(View.GONE);

            if (isUserPleno.equals("0")) {
                button_container.setVisibility(View.GONE);
            } else {
                button_container.setVisibility(View.VISIBLE);
            }

            leftLine.setBackgroundColor(ContextCompat.getColor(container.getContext(), R.color.lineGrey));
            if (applicant.getTestMethod() != null) {
                if (applicant.getTestMethod().equals("portfolio")) {
                    testMethod.setText(context.getString(R.string.test_status)+" "+context.getString(R.string.test_portfolio));
                    leftLine.setBackgroundColor(ContextCompat.getColor(container.getContext(), R.color.lineBlue));
                } else if (applicant.getTestMethod().equals("competency")) {
                    testMethod.setText(context.getString(R.string.test_status)+" "+context.getString(R.string.test_competency));
                    leftLine.setBackgroundColor(ContextCompat.getColor(container.getContext(), R.color.lineOrange));
                } else {
                    testMethod.setText(context.getString(R.string.test_status)+" -");
                }
            } else {
                testMethod.setText(context.getString(R.string.test_status)+" -");
            }

            if (applicant.getStatusGraduation().equals("L")) {
                container.setBackgroundColor(ContextCompat.getColor(container.getContext(), R.color.md_green_300));
                namaPeserta.setTextColor(ContextCompat.getColor(namaPeserta.getContext(), R.color.white));
                kompetensiPeserta.setTextColor(ContextCompat.getColor(kompetensiPeserta.getContext(), R.color.white));
                testMethod.setTextColor(ContextCompat.getColor(kompetensiPeserta.getContext(), R.color.white));
                positiveButton.setVisibility(View.GONE);
                negativeButton.setVisibility(View.GONE);
                editButton.setVisibility(View.VISIBLE);
            } else if (applicant.getStatusGraduation().equals("TL")) {
                container.setBackgroundColor(ContextCompat.getColor(container.getContext(), R.color.md_red_300));
                namaPeserta.setTextColor(ContextCompat.getColor(namaPeserta.getContext(), R.color.white));
                testMethod.setTextColor(ContextCompat.getColor(kompetensiPeserta.getContext(), R.color.white));
                kompetensiPeserta.setTextColor(ContextCompat.getColor(kompetensiPeserta.getContext(), R.color.white));
                positiveButton.setVisibility(View.GONE);
                negativeButton.setVisibility(View.GONE);
                editButton.setVisibility(View.VISIBLE);
            } else {
                container.setBackgroundColor(ContextCompat.getColor(container.getContext(), R.color.md_grey_100));
                namaPeserta.setTextColor(ContextCompat.getColor(namaPeserta.getContext(), R.color.primaryTextColor));
                kompetensiPeserta.setTextColor(ContextCompat.getColor(kompetensiPeserta.getContext(), R.color.secondaryTextColor));
                testMethod.setTextColor(ContextCompat.getColor(kompetensiPeserta.getContext(), R.color.secondaryTextColor));
                positiveButton.setVisibility(View.VISIBLE);
                negativeButton.setVisibility(View.VISIBLE);
            }

            if (applicant.getApplicantId().equals("0"))
                namaPeserta.setText(applicant.getFullName());
            else
                namaPeserta.setText(applicant.getFirstName() + " " + applicant.getLastName());

            kompetensiPeserta.setText(applicant.getCompetenceFieldLable());
            positiveButton.setOnClickListener(view -> {
                DialogWithDescription dialog = new DialogWithDescription(view.getContext());
                dialog.setDialogTitle(context.getString(R.string.pass_graduate_title));
                dialog.setDialogTextDesc(context.getString(R.string.graduate_desc));
                dialog.setPositiveButtonText(context.getString(R.string.yes));
                dialog.setNegativeButtonText(context.getString(R.string.cancel));
                dialog.positiveButton(v -> {
                    applicant.setStatusGraduation("L");
                    recyclerViewListener.onItemChooseCallback(v, applicant, index);
                    dialog.dismiss();
                });
                dialog.negativeButton(v -> dialog.dismiss());
                dialog.showDialog();
            });
            negativeButton.setOnClickListener(view -> {
                DialogWithDescription dialog = new DialogWithDescription(view.getContext());
                dialog.setDialogTitle(context.getString(R.string.unpass_graduate_title));
                dialog.setDialogTextDesc(context.getString(R.string.graduate_desc));
                dialog.setPositiveButtonText(context.getString(R.string.yes));
                dialog.setNegativeButtonText(context.getString(R.string.cancel));
                dialog.positiveButton(v -> {
                    applicant.setStatusGraduation("TL");
                    recyclerViewListener.onItemChooseCallback(v, applicant, index);
                    dialog.dismiss();
                });
                dialog.negativeButton(v -> dialog.dismiss());
                dialog.showDialog();
            });
            editButton.setOnClickListener(v -> {
                applicant.setStatusGraduation("NONE");
                recyclerViewListener.onItemChooseCallback(v, applicant, index);
            });

            container.setOnClickListener(view -> {
                Intent i = new Intent(view.getContext(), DetailApplicant.class);
                i.putExtra(ApplicantEntity.ASSESSMENT_APPLICANT_ID, applicant.getAssessmentApplicantId());
                i.putExtra(ApplicantEntity.APPLICANT_ID, applicant.getApplicantId());
                i.putExtra(AssessmentEntity.ASSESSMENT_ID, assessmentId);
                i.putExtra(AssessmentEntity.STATUS_ASSESSMENT, StatusKegiatanEntity.PLENO_DOC_COMPLETED);
                view.getContext().startActivity(i);
            });
        }
    }

    public void addApplicants(List<Applicant> applicantList) {

        for (Applicant applicant : applicantList) {
            applicants.add(applicant);
        }
        notifyDataSetChanged();
    }
}
