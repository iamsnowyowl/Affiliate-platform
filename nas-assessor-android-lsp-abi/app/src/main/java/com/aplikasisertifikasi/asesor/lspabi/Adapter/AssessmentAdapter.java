package com.aplikasisertifikasi.asesor.lspabi.Adapter;

import android.content.Context;
import android.content.Intent;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.LinearLayout;
import android.widget.RelativeLayout;
import android.widget.TextView;
import android.widget.Toast;

import com.aplikasisertifikasi.asesor.lspabi.Entity.AssessmentEntity;
import com.aplikasisertifikasi.asesor.lspabi.Entity.StatusKegiatanEntity;
import com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.Home.DetailHome.DetailHome;
import com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.Home.DetailHome.Pleno.AssessmentPlenoActivity;
import com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.Home.LokasiUjian.LokasiUjian;
import com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.Home.SuratMenyurat.SuratMenyuratActivity;
import com.aplikasisertifikasi.asesor.lspabi.Model.AssessmentSchedule;
import com.aplikasisertifikasi.asesor.lspabi.R;
import com.nex3z.notificationbadge.NotificationBadge;

import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.List;

import butterknife.BindView;
import butterknife.ButterKnife;

public class AssessmentAdapter extends RecyclerView.Adapter<RecyclerView.ViewHolder> {

    private List<AssessmentSchedule> assessmentSchedules;
    private Context context;
    private UserRoleType userRoleType;

    public enum UserRoleType {
        ASSESSOR,
        MANAGEMENT
    }

    public AssessmentAdapter(Context context, List<AssessmentSchedule> assessmentSchedules, UserRoleType userRoleType) {
        this.context = context;
        this.assessmentSchedules = assessmentSchedules;
        this.userRoleType = userRoleType;
        this.notifyDataSetChanged();
    }

    public interface ViewBinding {
        void bind(int index);
    }

    abstract class BaseViewHolder extends RecyclerView.ViewHolder implements ViewBinding {

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
        ViewBinding viewBinding = (BaseViewHolder) holder;

        viewBinding.bind(position);
    }

    RecyclerView.ViewHolder dispatchViewHolder(ViewGroup parent) {
        if (userRoleType == UserRoleType.ASSESSOR)
            return new AssessorViewHolder(LayoutInflater.from(parent.getContext())
                    .inflate(R.layout.row_kegiatan,
                            parent,
                            false)
            );
        else if (userRoleType == UserRoleType.MANAGEMENT)
            return new ManagementViewHolder(LayoutInflater.from(parent.getContext())
                    .inflate(R.layout.row_kegiatan_management,
                            parent,
                            false)
            );
        else
            throw new UnsupportedOperationException("Adapter type not spesified");
    }

    @Override
    public int getItemCount() {
        return assessmentSchedules.size();
    }

    class AssessorViewHolder extends BaseViewHolder {
        @BindView(R.id.kegiatanAsesmen)
        TextView namakegiatan;
        @BindView(R.id.tglAsesmen)
        TextView tanggalKegiatan;
        @BindView(R.id.noteAsesmen)
        TextView noteAsesmen;
        @BindView(R.id.btnPraAssessment)
        RelativeLayout btnpraAssessment;
        @BindView(R.id.btnAssessment)
        RelativeLayout btnAssessment;
        @BindView(R.id.btnPleno)
        RelativeLayout btnPlenoAssessment;
        @BindView(R.id.assessment_container)
        LinearLayout assessmentContainer;
        @BindView(R.id.status_assessment)
        TextView statusAssessment;
        @BindView(R.id.counter_badge_asesmen)
        NotificationBadge badgeAsesmen;
        @BindView(R.id.counter_badge_pleno)
        NotificationBadge badgePleno;

        public AssessorViewHolder(View itemView) {
            super(itemView);
            ButterKnife.bind(this, itemView);
        }

        @Override
        public void bind(int index) {
            AssessmentSchedule assessmentSchedule = assessmentSchedules.get(index);
            SimpleDateFormat date = new SimpleDateFormat("yyyy-MM-dd hh:mm:ss");
            SimpleDateFormat dateFormat = new SimpleDateFormat("dd MMMM yyyy");

            namakegiatan.setText(assessmentSchedule.getTitle());
            try {
                tanggalKegiatan.setText(dateFormat.format(date.parse(assessmentSchedule.getStartDate())));
            } catch (ParseException e) {
                e.printStackTrace();
            }

            if (assessmentSchedule.getIsUserPleno() == 1) {
                badgePleno.setVisibility(View.VISIBLE);
                badgePleno.setNumber(assessmentSchedule.getCountEmptyGraduation());
            }

            if (assessmentSchedule.getLastStateSchedule().equals(StatusKegiatanEntity.REAL_ASSESSMENT)) {
                badgeAsesmen.setVisibility(View.VISIBLE);
                badgeAsesmen.setNumber(assessmentSchedule.getCountEmptyRecomendation());
            }

            switch (assessmentSchedule.getLastStateSchedule()) {
                case StatusKegiatanEntity.ON_REVIEW_APPLICANT_DOCUMENT:
                    statusAssessment.setVisibility(View.VISIBLE);
                    statusAssessment.setText(R.string.pra_assessment);
                    break;
                case StatusKegiatanEntity.ON_COMPLETED_REPORT:
                    statusAssessment.setVisibility(View.VISIBLE);
                    statusAssessment.setText(R.string.pra_assessment_selesai);
                    break;
                case StatusKegiatanEntity.REAL_ASSESSMENT:
                    statusAssessment.setVisibility(View.VISIBLE);
                    statusAssessment.setText(R.string.assement);
                    break;
                case StatusKegiatanEntity.PLENO_DOC_COMPLETED:
                    statusAssessment.setVisibility(View.VISIBLE);
                    statusAssessment.setText(R.string.pleno);
                    btnPlenoAssessment.setVisibility(View.VISIBLE);
                    btnpraAssessment.setVisibility(View.GONE);
                    break;
                case StatusKegiatanEntity.PLENO_REPORT_READY:
                    statusAssessment.setVisibility(View.VISIBLE);
                    statusAssessment.setText(R.string.pleno_selesai);
                    btnPlenoAssessment.setVisibility(View.VISIBLE);
                    btnpraAssessment.setVisibility(View.GONE);

                    break;
                case StatusKegiatanEntity.PRINT_CERTIFICATE:
                    statusAssessment.setVisibility(View.VISIBLE);
                    statusAssessment.setText(R.string.print_certificate);
                    break;
                case StatusKegiatanEntity.COMPLETED:
                    statusAssessment.setVisibility(View.VISIBLE);
                    statusAssessment.setText(R.string.completed);
                    break;
                default:
                    statusAssessment.setVisibility(View.VISIBLE);
                    statusAssessment.setText(R.string.soon);
            }

            noteAsesmen.setText(assessmentSchedule.getNotes());
            itemView.setOnClickListener(v -> {
                Intent intent = new Intent(v.getContext(), LokasiUjian.class);
                intent.putExtra(AssessmentEntity.ASSESSMENT_ID, assessmentSchedule.getScheduleAssessmentID());
                intent.putExtra(AssessmentEntity.LAT_ASSESSMENT, assessmentSchedule.getLatitude());
                intent.putExtra(AssessmentEntity.LONG_ASSESSMENT, assessmentSchedule.getLongitude());
                intent.putExtra(AssessmentEntity.ADDRESS_ASSESSMENT, assessmentSchedule.getAddress());
                intent.putExtra(AssessmentEntity.TITLE_ASSESSMENT, assessmentSchedule.getTitle());
                intent.putExtra(AssessmentEntity.NOTE_ASSESSMENT, assessmentSchedule.getNotes());
                intent.putExtra(AssessmentEntity.STARTDATE_ASSESSMENT, assessmentSchedule.getStartDate());
                intent.putExtra(AssessmentEntity.TUK_ASSESSMENT, assessmentSchedule.getTukId());
                v.getContext().startActivity(intent);
            });

            btnpraAssessment.setOnClickListener(v -> {
                Intent intent = new Intent(v.getContext(), DetailHome.class);
                intent.putExtra(AssessmentEntity.ASSESSMENT_ID, assessmentSchedule.getScheduleAssessmentID());
                intent.putExtra(AssessmentEntity.STATUS_ASSESSMENT, StatusKegiatanEntity.ON_REVIEW_APPLICANT_DOCUMENT);
                intent.putExtra(AssessmentEntity.TITLE_ASSESSMENT, assessmentSchedule.getTitle());
                intent.putExtra(AssessmentEntity.NOTE_ASSESSMENT, assessmentSchedule.getNotes());
                intent.putExtra(AssessmentEntity.STARTDATE_ASSESSMENT, assessmentSchedule.getStartDate());
                v.getContext().startActivity(intent);
            });

            btnPlenoAssessment.setOnClickListener(view -> {
                Intent intent = new Intent(view.getContext(), AssessmentPlenoActivity.class);
                intent.putExtra(AssessmentEntity.ASSESSMENT_ID, assessmentSchedule.getScheduleAssessmentID());
                intent.putExtra(AssessmentEntity.TITLE_ASSESSMENT, assessmentSchedule.getTitle());
                intent.putExtra(AssessmentEntity.STARTDATE_ASSESSMENT, assessmentSchedule.getStartDate());
                intent.putExtra(AssessmentEntity.USER_PLENO, String.valueOf(assessmentSchedule.getIsUserPleno()));
                view.getContext().startActivity(intent);
            });

            btnAssessment.setOnClickListener(v -> {
                        if ((assessmentSchedule.getLastStateSchedule().equals(StatusKegiatanEntity.REAL_ASSESSMENT) && assessmentSchedule.getIsUserAssessment() == 1) ||
                                (assessmentSchedule.getLastStateSchedule().equals(StatusKegiatanEntity.REAL_ASSESSMENT) && assessmentSchedule.getLastStateSchedule().equals(StatusKegiatanEntity.PLENO_DOC_COMPLETED) && assessmentSchedule.getIsUserPleno() == 1)) {
                            Intent intent = new Intent(v.getContext(), DetailHome.class);
                            intent.putExtra(AssessmentEntity.ASSESSMENT_ID, assessmentSchedule.getScheduleAssessmentID());
                            intent.putExtra(AssessmentEntity.STATUS_ASSESSMENT, StatusKegiatanEntity.REAL_ASSESSMENT);
                            intent.putExtra(AssessmentEntity.TITLE_ASSESSMENT, assessmentSchedule.getTitle());
                            intent.putExtra(AssessmentEntity.NOTE_ASSESSMENT, assessmentSchedule.getNotes());
                            intent.putExtra(AssessmentEntity.STARTDATE_ASSESSMENT, assessmentSchedule.getStartDate());
                            v.getContext().startActivity(intent);
                        } else {
                            Toast.makeText(context, R.string.cannot_access_assessment, Toast.LENGTH_LONG).show();
                        }
                    }
            );
        }
    }

    class ManagementViewHolder extends BaseViewHolder {
        @BindView(R.id.kegiatanAsesmen)
        TextView namakegiatan;
        @BindView(R.id.tglAsesmen)
        TextView tanggalKegiatan;
        @BindView(R.id.noteAsesmen)
        TextView noteAsesmen;
        @BindView(R.id.btnSuratMenyurat)
        RelativeLayout btnSuratMenyurat;

        public ManagementViewHolder(View inflate) {
            super(inflate);
            ButterKnife.bind(this, inflate);
        }

        @Override
        public void bind(int index) {
            AssessmentSchedule assessmentSchedule = assessmentSchedules.get(index);
            SimpleDateFormat date = new SimpleDateFormat("yyyy-MM-dd hh:mm:ss");
            SimpleDateFormat dateFormat = new SimpleDateFormat("dd MMMM yyyy");
            namakegiatan.setText(assessmentSchedule.getTitle());
            try {
                tanggalKegiatan.setText(dateFormat.format(date.parse(assessmentSchedule.getStartDate())));
            } catch (ParseException e) {
                e.printStackTrace();
            }
            noteAsesmen.setText(assessmentSchedule.getNotes());
            itemView.setOnClickListener(v -> {
                Intent intent = new Intent(v.getContext(), LokasiUjian.class);
                intent.putExtra(AssessmentEntity.ASSESSMENT_ID, assessmentSchedule.getScheduleAssessmentID());
                intent.putExtra(AssessmentEntity.LAT_ASSESSMENT, assessmentSchedule.getLatitude());
                intent.putExtra(AssessmentEntity.LONG_ASSESSMENT, assessmentSchedule.getLongitude());
                intent.putExtra(AssessmentEntity.ADDRESS_ASSESSMENT, assessmentSchedule.getAddress());
                intent.putExtra(AssessmentEntity.TITLE_ASSESSMENT, assessmentSchedule.getTitle());
                intent.putExtra(AssessmentEntity.NOTE_ASSESSMENT, assessmentSchedule.getNotes());
                intent.putExtra(AssessmentEntity.STARTDATE_ASSESSMENT, assessmentSchedule.getStartDate());
                intent.putExtra(AssessmentEntity.TUK_ASSESSMENT, assessmentSchedule.getTukId());
                v.getContext().startActivity(intent);
            });

            btnSuratMenyurat.setOnClickListener(view -> {
                Intent intent = new Intent(view.getContext(), SuratMenyuratActivity.class);
                intent.putExtra(AssessmentEntity.ASSESSMENT_ID, assessmentSchedule.getScheduleAssessmentID());
                view.getContext().startActivity(intent);
            });

        }
    }

    public void addAssessmentSchedules(List<AssessmentSchedule> assessmentSchedulesList) {

        for (AssessmentSchedule assessmentSchedule : assessmentSchedulesList) {
            assessmentSchedules.add(assessmentSchedule);
        }
        notifyDataSetChanged();
    }
}
